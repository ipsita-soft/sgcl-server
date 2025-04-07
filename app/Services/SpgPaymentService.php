<?php

namespace App\Services;

use App\Http\Controllers\ApiResponseController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\FeeRemindersResource;
use App\Models\FeeReminders;
use App\Models\Payments;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Models\Settings;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SpgPaymentService extends ApiResponseController
{
    protected $urlAccessToken = 'https://spg.com.bd:6314/api/v2/SpgService/GetAccessToken';
    protected $urlSessionToken = 'https://spg.com.bd:6314/api/v2/SpgService/CreatePaymentRequest';
    protected $urlPaymentVerify = 'https://spg.com.bd:6314/api/v2/SpgService/TransactionVerificationWithToken';
    protected $urlApplicationFee = 'https://sgcl.digitalprogressbd.com/application-fee';
    protected $urlServiceFee = 'https://sgcl.digitalprogressbd.com/service-fee';

    public function getSessionToken()
    {
        try {
            $paymentGetaway = Settings::where('module', 'PaymentGetaway')->firstOrFail();
            $accountInfo = json_decode($paymentGetaway->data, true);
            $invoiceNo = 'INV' . date('Ymd') . random_int(100000, 999999);

            $accessData = [
                "AccessUser" => [
                    "userName" => $accountInfo['user_name'],
                    "password" => $accountInfo['password'],
                ],
                "invoiceNo" => $invoiceNo,
                "amount" => $accountInfo['credit_amount'],
                "invoiceDate" => date('Y-m-d'),
                "accounts" => [
                    [
                        "crAccount" => $accountInfo['credit_account'],
                        "crAmount" => $accountInfo['credit_amount'],
                    ],
                ],
            ];

            $accessResponse = Http::withBasicAuth($accountInfo['user_name'], $accountInfo['password'])->post($this->urlAccessToken, $accessData);

            if ($accessResponse->successful()) {
                $dataAccessToken = $accessResponse->json();

                if ($dataAccessToken['status'] == 200) {
                    $sessionData = [
                        "authentication" => [
                            "apiAccessUserId" => $accountInfo['user_name'],
                            "apiAccessToken" => $dataAccessToken['access_token'],
                        ],
                        "referenceInfo" => [
                            "InvoiceNo" => $invoiceNo,
                            "invoiceDate" => date('Y-m-d'),
                            "returnUrl" => $this->urlApplicationFee,
                            "totalAmount" => $accountInfo['credit_amount'],
                            "applicentName" => auth()->user()->name,
                            "applicentContactNo" => auth()->user()->phone,
                            "extraRefNo" => time()
                        ],
                        "creditInformations" => [
                            [
                                "slno" => 1,
                                "crAccount" => $accountInfo['credit_account'],
                                "crAmount" => $accountInfo['credit_amount'],
                                "tranMode" => "TRN",
                            ],
                        ],
                    ];

                    $sessionResponse = Http::withBasicAuth($accountInfo['user_name'], $accountInfo['password'])->post($this->urlSessionToken, $sessionData);

                    if ($sessionResponse->successful()) {
                        return $sessionResponse->json();
                    }
                }

                return ['status' => 404, 'session_token' => NULL, 'message' => 'Invalid session token response'];
            } else {
                return ['status' => 404, 'session_token' => NULL, 'message' => 'Failed to retrieve token'];
            }
        } catch (\Exception $e) {
            return ['status' => 404, 'session_token' => NULL, 'message' => $e->getMessage()];
        }
    }

    function paymentVerification($token){
        try {
            $paymentGetaway = Settings::where('module', 'PaymentGetaway')->firstOrFail();
            $accountInfo = json_decode($paymentGetaway->data, true);

            $verifyResponse = Http::withBasicAuth($accountInfo['user_name'], $accountInfo['password'])->post($this->urlPaymentVerify,['session_Token' => $token]);

            if ($verifyResponse->successful()) {
                return $verifyResponse->json();
            }

            return ['status' => 404, 'msg' => 'Invalid action'];
        } catch (ModelNotFoundException $e) {
            return ['status' => 404, 'msg' => $e->getMessage()];
        } catch (\Exception $e) {
            return ['status' => 404, 'msg' => $e->getMessage()];
        }
    }

    public function serviceFeeSessionToken($id)
    {
        try {
            $feeReminder = FeeReminders::where(['id' => $id,'send_to' => auth()->user()->id])->firstOrFail();        
            $paymentGetaway = Settings::where('module', 'PaymentGetaway')->firstOrFail();
            $accountInfo = json_decode($paymentGetaway->data, true);
            $invoiceNo = 'INV' . date('Ymd') . random_int(100000, 999999);

            $accessData = [
                "AccessUser" => [
                    "userName" => $accountInfo['user_name'],
                    "password" => $accountInfo['password'],
                ],
                "invoiceNo" => $invoiceNo,
                "amount" => $feeReminder->amount,
                "invoiceDate" => date('Y-m-d'),
                "accounts" => [
                    [
                        "crAccount" => $accountInfo['credit_account'],
                        "crAmount" => $feeReminder->amount,
                    ],
                ],
            ];
            
            $accessResponse = Http::withBasicAuth($accountInfo['user_name'], $accountInfo['password'])->post($this->urlAccessToken, $accessData);
            
            if ($accessResponse->successful()) {
                $dataAccessToken = $accessResponse->json();

                if ($dataAccessToken['status'] == 200) {
                    $sessionData = [
                        "authentication" => [
                            "apiAccessUserId" => $accountInfo['user_name'],
                            "apiAccessToken" => $dataAccessToken['access_token'],
                        ],
                        "referenceInfo" => [
                            "InvoiceNo" => $invoiceNo,
                            "invoiceDate" => date('Y-m-d'),
                            "returnUrl" => $this->urlServiceFee,
                            "totalAmount" => $feeReminder->amount,
                            "applicentName" => auth()->user()->name,
                            "applicentContactNo" => auth()->user()->phone,
                            "extraRefNo" => time()
                        ],
                        "creditInformations" => [
                            [
                                "slno" => 1,
                                "crAccount" => $accountInfo['credit_account'],
                                "crAmount" => $feeReminder->amount,
                                "tranMode" => "TRN",
                            ],
                        ],
                    ];

                    $sessionResponse = Http::withBasicAuth($accountInfo['user_name'], $accountInfo['password'])->post($this->urlSessionToken, $sessionData);

                    if ($sessionResponse->successful()) {
                        return $sessionResponse->json();
                    }
                }

                return ['status' => 404, 'session_token' => NULL, 'message' => 'Invalid session token response'];
            } else {
                return ['status' => 404, 'session_token' => NULL, 'message' => 'Failed to retrieve token'];
            }
        } catch (ModelNotFoundException $e) {
            return ['status' => 404, 'session_token' => NULL, 'message' => 'Failed to retrieve token'];
        } catch (\Exception $e) {
            return ['status' => 404, 'session_token' => NULL, 'message' => $e->getMessage()];
        }
    }
}
