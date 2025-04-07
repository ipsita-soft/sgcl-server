<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }



    public function childLocations()
    {
        return $this->hasMany(Location::class, 'parent_id');
    }

    /**
     * Get the parent department for the department.
     */
    public function parentLocation()
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }
    public function landOwnership()
    {
        return $this->belongsTo(LandOwnership::class, 'land_ownership_id');
    }
}
