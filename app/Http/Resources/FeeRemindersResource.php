<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Payment;
class FeeRemindersResource extends JsonResource
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
            'message' => $this->message,
            'amount' => $this->amount,
            'date' => $this->date,
            'status' => $this->status,
            'send_by' => $this->sender ? (new UserResource($this->sendBy))->only(['id', 'name']) : NULL,
            'send_to' => $this->send_to ? new SendToResource($this->sendTo) : NULL,
            'payments' => $this->payment ? new PaymentResource($this->payment) : NULL,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at

        ];
    }
}
