<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class FeeReminders extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['message', 'amount', 'date', 'status', 'sender', 'send_to'];

    function sendBy(){
        return $this->belongsTo(User::class, 'sender', 'id');
    }

    function sendTo(){
        return $this->belongsTo(User::class, 'send_to', 'id');
    }


    public function payment(){
        return $this->hasOne(Payments::class,'fee_reminder_id');
    }
}
