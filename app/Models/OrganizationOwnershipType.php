<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationOwnershipType extends Model
{
    use HasFactory;

    public function organization(){
        return $this->hasMany(Organization::class);
    }
}
