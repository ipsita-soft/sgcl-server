<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => $this->user_id ? new SendToResource($this->user) : NULL,
            'fee_reminder_id' => $this->fee_reminder_id,
            'invoice_no' => $this->invoice_no,
            'invoice_date' => $this->invoice_date,
            'amount' => $this->amount,
            'total_amount' => $this->total_amount,
            'name_of_payee' => $this->name_of_payee,
            'mobile_of_payee' => $this->mobile_of_payee,
            'session_token' => $this->session_token,
            'transaction_id' => $this->transaction_id,
            'transaction_date' => $this->transaction_date,
            'pay_mode' => $this->pay_mode,
            'payment_status' => $this->payment_status,
            'payment_type' => $this->payment_type,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
