<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MessageAttachmentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'message_id' => $this->message_id,
            'file_path' => $this->file_path ? asset('storage/'.$this->file_path) : '',
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'url' => $this->file_path ? asset('storage/'.$this->file_path) : '',
        ];
    }
}
