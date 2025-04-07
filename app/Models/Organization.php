<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Organization extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];

    public function organizationCategory(){
        return $this->belongsTo(OrganizationCategory::class);
    }

    public function ownershipType(){
        return $this->belongsTo(OrganizationOwnershipType::class, 'organization_ownership_type_id');
    }

    public function industryType(){
        return $this->belongsTo(IndustryType::class);
    }

    public function applianceBurnerDetails(){
        return $this->hasMany(ApplianceAndBurnerDetails::class,'organization_id');
    }
    public function applianceBurnerInfo(){
        return $this->hasOne(ApplianceAndBurnerInfo::class,'organization_id');
    }
    public function attachment(){
        return $this->hasOne(Attachment::class,'organization_id');
    }
    public function authorityContactDetails(){
        return $this->hasMany(AuthorityContactDetails::class,'organization_id');
    }
    public function expectedGasNeed(){
        return $this->hasMany(ExpectedGasNeed::class,'organization_id');
    }
    public function financialInformation(){
        return $this->hasOne(FinancialInformation::class,'organization_id');
    }
    public function ingredientsInfoForProduction(){
        return $this->hasMany(IngredientsInfoForProduction::class,'organization_id');
    }
    public function location(){
        return $this->hasOne(Location::class,'organization_id');
    }
    public function manufacturingData(){
        return $this->hasOne(ManufacturingData::class,'organization_id');
    }
    public function organizationOwnersDirector(){
        return $this->hasMany(OrganizationOwnersDirector::class,'organization_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
