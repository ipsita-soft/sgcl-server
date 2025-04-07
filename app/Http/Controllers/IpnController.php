<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpgIpnRequest;
use App\Services\SpgPaymentService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Payments;
use App\Models\Settings;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class IpnController extends ApiResponseController
{
    protected $spgPaymentService;

    public function __construct(SpgPaymentService $spgPaymentService)
    {
        $this->spgPaymentService = $spgPaymentService;
    }

    public function spgIpn(SpgIpnRequest $request)
    {
        $setting = Settings::select('data')->where('name', 'SPG')->firstOrFail();
        $settings = json_decode($setting->data);

        if ($settings->user_name == $request->credential['username'] && $settings->password == $request->credential['password']) {
            $payment = Payments::select('id', 'session_token', 'payment_status', 'transaction_id', 'invoice_no', 'payment_type')->where('session_token', $request->data['session_token'])->first();

            if ($payment && $payment->payment_status == 'Paid') {
                return response()->json([
                    'status' => '200',
                    'msg' => 'Success',
                    'transactionid' => $payment->transaction_id
                ], 200);
            } else if ($payment && $payment->payment_type == 'Application Fee') {
                $response = $this->storeApplicationFee($request->data['session_token']);
                return response()->json([
                    'status' => $response['status'],
                    'msg' => $response['msg'],
                    'transactionid' => $response['TransactionId']
                ], 200);
            } else if ($payment && $payment->payment_type == 'Service Fee') {
                $response = $this->storeServiceFee($request->data['session_token']);

                return response()->json([
                    'status' => $response['status'],
                    'msg' => $response['msg'],
                    'transactionid' => $response['TransactionId']
                ], 200);
            } else {
                return response()->json([
                    'status' => '5555',
                    'msg' => 'Unable to proceed/proceed to refund',
                    'transactionid' => NULL,
                ], 200);
            }
        } else {
            return response()->json([
                'status' => '401',
                'msg' => 'Authorization has been denied for this request.',
                'transactionid' => NULL,
            ], 200);
        }
    }

    private function payMode($value = null)
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
              return $modeList[$value] ?? 'Other PayMode';
        } else {
            return $modeList;
        }
    }

    private function storeApplicationFee($token)
    {
        try {
            $spgResponse = $this->spgPaymentService->paymentVerification($token);

            DB::beginTransaction();

            if ($spgResponse['status'] == 200) {
                $payment = Payments::where([
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
                    'session_token' => $token
                ])->firstOrFail()->update([
                    'payment_status' => "Cancel",
                ]);
            }

            DB::commit();

            return $spgResponse;
        } catch (ModelNotFoundException $e) {
            return $spgResponse;
        } catch (\Exception $e) {
            DB::rollBack();
            return $spgResponse;
        }
    }

    private function storeServiceFee($token)
    {
        try {
            $spgResponse = $this->spgPaymentService->paymentVerification($token);

            DB::beginTransaction();

            if ($spgResponse['status'] == 200) {
                $payment = Payments::where([
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
                    'session_token' => $token
                ])->firstOrFail()->update([
                    'payment_status' => "Cancel",
                ]);
            }

            DB::commit();

            return $spgResponse;
        } catch (ModelNotFoundException $e) {
            return $spgResponse;
        } catch (\Exception $e) {
            DB::rollBack();
            return $spgResponse;
        }
    }
}
