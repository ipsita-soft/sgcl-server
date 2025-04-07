<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'loggable_type',
        'loggable_id',
        'source_type',
        'source_id',
        'new_data',
        'old_data',
        'module',
    ];
}
