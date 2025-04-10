<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialInformation extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded;
    public function organization(){
        return $this->belongsTo(Organization::class,'organization_id','id');
    }
}
