<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaymentResource;
use App\Services\SpgPaymentService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Payments;
use App\Http\Requests\StorePaymentsRequest;
use App\Http\Requests\UpdatePaymentsRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PaymentsController extends ApiResponseController
{
    protected $spgPaymentService;

    public function __construct(SpgPaymentService $spgPaymentService)
    {
        $this->spgPaymentService = $spgPaymentService;
    }

    public function applicationFeeProcess()
    {
        try {
            $spgResponse = $this->spgPaymentService->getSessionToken();

            if ($spgResponse['status'] == 200) {
                try {
                    DB::beginTransaction();
                    Payments::updateOrCreate(
                        ['user_id' => auth()->user()->id, 'payment_type' => 'Application Fee'],
                        ['session_token' => $spgResponse['session_token'], 'status' => 1]
                    );

                    DB::commit();
                    return $this->successResponse(
                        message: $spgResponse['message'], status: $spgResponse['status'], data: $spgResponse['session_token']
                    );

                } catch (\Exception $e) {
                    DB::rollBack();
                    return $this->errorResponse(
                        message: $e->getMessage()
                    );
                }
            } else {
                return $this->errorResponse(
                    message: $spgResponse['message'], status: $spgResponse['status'], data: $spgResponse['session_token']
                );
            }
        } catch (\Exception $e) {
            return $this->errorResponse(
                message: $e->getMessage()
            );
        }
    }

    public function applicationPaymentCheck()
    {
        try {
            return Payments::where('user_id', auth()->user()->id)
                ->where('payment_type', 'Application Fee')
                ->select('id', 'user_id', 'payment_status', 'payment_type')
                ->firstOrFail();

        } catch (ModelNotFoundException $e) {
            return $this->dataNotFoundResponse();
        } catch (\Exception $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
            );
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $userId = $request->user_id;

            if (auth()->user()?->role->name == 'member' || auth()->user()?->role->name == 'applicant') {
                $userId = auth()->user()?->id;
            }

            $userPayments = Payments::with(['user', 'feeReminder'])
                ->when($userId, function ($query, $userId) {
                    return $query->where('user_id', $userId);
                })->when($request->invoice_no, function ($query, $invoice_no) {
                    return $query->where('invoice_no', $invoice_no);
                })->when($request->invoice_date, function ($query, $invoice_date) {
                    return $query->where('invoice_date', $invoice_date);
                })->when($request->total_amount, function ($query, $total_amount) {
                    return $query->where('total_amount', 'LIKE', '%' . $total_amount . '%');
                })->when($request->amount, function ($query, $amount) {
                    return $query->where('amount', $amount);
                })->when($request->name_of_payee, function ($query, $name_of_payee) {
                    return $query->where('name_of_payee', 'LIKE', '%' . $name_of_payee . '%');
                })->when($request->transaction_date, function ($query, $transaction_date) {
                    return $query->where('transaction_date', $transaction_date);
                })->when($request->payment_status, function ($query, $payment_status) {
                    return $query->where('payment_status', $payment_status);
                })->when($request->payment_type, function ($query, $payment_type) {
                    return $query->where('payment_type', $payment_type);
                })->when($request->created_at, function ($query, $created_at) {
                    return $query->whereDate('created_at', $created_at);
                })->paginate($request->per_page ?? 10);
            return PaymentResource::collection($userPayments);

        } catch (ModelNotFoundException $e) {
            return $this->dataNotFoundResponse();
        } catch (\Exception $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
            );
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    function payMode($value = null)
    {
        $modeList = [
            'A01' => 'Counter Payment',
            'A02' => 'Sonali Bank Account Transfer',
            'A05' => 'e-Wallet Payment',
            'M03' => 'Rocket (DBBL)',
            'M04' => 'bKash',
            'M08' => 'Nagad',
            'M09' => 'Upay',
            'M10' => 'Tap',
            'M11' => 'Ok-wallet',
            'M12' => 'Cellfin',
            'C07' => 'DBBL Nexus Card',
            'C08' => 'Visa (Govt. Merchant)',
            'C09' => 'Master (Govt. Merchant)',
            'C10' => 'Amex (Govt. Merchant)',
            'C11' => 'Visa (Education Merchant)',
            'C12' => 'Master (Education Merchant)',
            'C13' => 'Amex (Education Merchant)',
            'C14' => 'Visa (Utility Merchant)',
            'C15' => 'Master (Utility Merchant)',
            'C16' => 'Amex (Utility Merchant)',
            'C21' => 'Visa (USD req, USD Settlement)',
            'C22' => 'Master (USD req, USD Settlement)',
            'C23' => 'Amex (USD req, USD Settlement)',
            'C24' => 'Visa (Local PAN off, BDT settlement)',
            'C25' => 'Master (Local PAN off, BDT settlement)',
            'C26' => 'Amex (Local PAN off, BDT settlement)',
            'ANY' => 'N/A',
        ];


        if (!empty($value)) {
            return $modeList[$value] ?? 'Other  PayMode';
        } else {
            return $modeList;
        }
    }

    public function storeApplicationFee($token)
    {
        try {
            $spgResponse = $this->spgPaymentService->paymentVerification($token);

            DB::beginTransaction();
            if ($spgResponse['status'] == 200) {
                $payment = Payments::where([
                    'user_id' => auth()->user()->id,
                    'session_token' => $token
                ])->firstOrFail();
                $payment->invoice_no = $spgResponse['InvoiceNo'];
                $payment->invoice_date = $spgResponse['InvoiceDate'];
                $payment->amount = $spgResponse['TotalAmount'];
                $payment->total_amount = $spgResponse['PayAmount'];
                $payment->name_of_payee = $spgResponse['ApplicantName'];
                $payment->mobile_of_payee = $spgResponse['ApplicantContactNo'];
                $payment->transaction_id = $spgResponse['TransactionId'];
                $payment->transaction_date = $spgResponse['TransactionDate'];
                $payment->pay_mode = $this->payMode($spgResponse['PayMode']);
                $payment->payment_status = "Paid";
                $payment->save();
                $payment->user->application_fee = "Paid";
                $payment->user->save();
            } else if ($spgResponse['status'] == 201 && $spgResponse['msg'] == 'Unpaid') {
                Payments::where([
                    'user_id' => auth()->user()->id,
                    'session_token' => $token
                ])->firstOrFail()->update([
                    'invoice_no' => $spgResponse['InvoiceNo'],
                    'invoice_date' => $spgResponse['InvoiceDate'],
                    'amount' => $spgResponse['TotalAmount'],
                    'total_amount' => $spgResponse['PayAmount'],
                    'name_of_payee' => $spgResponse['ApplicantName'],
                    'mobile_of_payee' => $spgResponse['ApplicantContactNo'],
                    'transaction_id' => $spgResponse['TransactionId'],
                    'transaction_date' => $spgResponse['TransactionDate'],
                    'pay_mode' => $this->payMode($spgResponse['PayMode']),
                    'payment_status' => "Unpaid",
                ]);
            } else if ($spgResponse['status'] == 400) {
                $payment = Payments::where([
                    'user_id' => auth()->user()->id,
                    'session_token' => $token
                ])->firstOrFail()->update([
                    'payment_status' => "Cancel",
                ]);
            }

            DB::commit();

            return $this->successResponse(message: $spgResponse['msg'], status: $spgResponse['status'], data: $spgResponse);
        } catch (ModelNotFoundException $e) {
            return $this->dataNotFoundResponse(message: $spgResponse['msg'], status: $spgResponse['status'], data: $spgResponse);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(message: $e->getMessage());
        }
    }

    public function serviceFeeProcess($id)
    {
        try {
            $spgResponse = $this->spgPaymentService->serviceFeeSessionToken($id);
            if ($spgResponse['status'] == 200) {
                try {
                    DB::beginTransaction();
                    Payments::updateOrCreate(
                        ['user_id' => auth()->user()->id, 'fee_reminder_id' => $id],
                        ['session_token' => $spgResponse['session_token'], 'payment_type' => 'Service Fee', 'status' => 1]
                    );

                    DB::commit();
                    return $this->successResponse(
                        message: $spgResponse['message'], status: $spgResponse['status'], data: $spgResponse['session_token']
                    );

                } catch (\Exception $e) {
                    DB::rollBack();
                    return $this->errorResponse(
                        message: $e->getMessage()
                    );
                }
            } else {
                return $this->errorResponse(
                    message: $spgResponse['message'], status: $spgResponse['status'], data: $spgResponse['session_token']
                );
            }
        } catch (ModelNotFoundException $e) {
            return $this->dataNotFoundResponse();
        } catch (\Exception $e) {
            return $this->errorResponse(
                message: $e->getMessage()
            );
        }
    }

    public function storeServiceFee($token)
    {
        try {
            $spgResponse = $this->spgPaymentService->paymentVerification($token);
        
            DB::beginTransaction();

            if ($spgResponse['status'] == 200) {
                $payment = Payments::where([
                    'user_id' => auth()->user()->id,
                    'session_token' => $token
                ])->firstOrFail();
                $payment->invoice_no = $spgResponse['InvoiceNo'];
                $payment->invoice_date = $spgResponse['InvoiceDate'];
                $payment->amount = $spgResponse['TotalAmount'];
                $payment->total_amount = $spgResponse['PayAmount'];
                $payment->name_of_payee = $spgResponse['ApplicantName'];
                $payment->mobile_of_payee = $spgResponse['ApplicantContactNo'];
                $payment->transaction_id = $spgResponse['TransactionId'];
                $payment->transaction_date = $spgResponse['TransactionDate'];
                $payment->pay_mode = $this->payMode($spgResponse['PayMode']);
                $payment->payment_status = "Paid";
                $payment->save();
            } else if ($spgResponse['status'] == 201 && $spgResponse['msg'] == 'Unpaid') {
                Payments::where([
                    'user_id' => auth()->user()->id,
                    'session_token' => $token
                ])->firstOrFail()->update([
                    'invoice_no' => $spgResponse['InvoiceNo'],
                    'invoice_date' => $spgResponse['InvoiceDate'],
                    'amount' => $spgResponse['TotalAmount'],
                    'total_amount' => $spgResponse['PayAmount'],
                    'name_of_payee' => $spgResponse['ApplicantName'],
                    'mobile_of_payee' => $spgResponse['ApplicantContactNo'],
                    'transaction_id' => $spgResponse['TransactionId'],
                    'transaction_date' => $spgResponse['TransactionDate'],
                    'pay_mode' => $this->payMode($spgResponse['PayMode']),
                    'payment_status' => "Unpaid",
                ]);
            } else if ($spgResponse['status'] == 400) {
                $payment = Payments::where([
                    'user_id' => auth()->user()->id,
                    'session_token' => $token
                ])->firstOrFail()->update([
                    'payment_status' => "Cancel",
                ]);
            }

            DB::commit();

            return $this->successResponse(message: $spgResponse['msg'], status: $spgResponse['status'], data: $spgResponse);
        } catch (ModelNotFoundException $e) {
            return $this->dataNotFoundResponse(message: $spgResponse['msg'], status: $spgResponse['status'], data: $spgResponse);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(message: $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $userPayment = Payments::with(['user', 'feeReminder'])
                ->where('id', $id)
                ->when(auth()->user()->role->name !== 'Admin' && auth()->user()->role->name !== 'Super Admin', function ($query) {
                    return $query->where('user_id', auth()->user()->id);
                })
                ->firstOrFail();
            return new PaymentResource($userPayment);
        } catch (ModelNotFoundException $e) {
            return $this->dataNotFoundResponse();
        } catch (\Exception $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($token)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentsRequest $request, Payments $payments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payments $payments)
    {
        //
    }
}
