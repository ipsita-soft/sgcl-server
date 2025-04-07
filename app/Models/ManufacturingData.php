<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManufacturingData extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded;

    public function organization(){
        return $this->belongsTo(Organization::class,'organization_id','id');
    }

    public function productionType(){
        return $this->belongsTo(ProductionTypes::class,'production_type_id');
    }
}
