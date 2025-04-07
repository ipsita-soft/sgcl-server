<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

use App\Http\Resources\OrganizationCategoryResource;
use App\Http\Resources\OrganizationOwnershipTypesResource;
use App\Http\Resources\IndustryTypesResource;
use App\Http\Resources\LandOwnershipResource;
use App\Http\Resources\ProductionTypesResource;

use App\Models\OrganizationCategory;
use App\Models\IndustryType;
use App\Models\OrganizationOwnershipType;
use App\Models\ProductionTypes;
use App\Models\LandOwnership;

use Illuminate\Http\Request;


class CatalogController extends Controller
{
    public function organizationCategories(Request $request) {
        $categories = OrganizationCategory::orderBy('sorting_index','ASC')->paginate($request->per_page ?? 15);
        return OrganizationCategoryResource::collection($categories);
    }

    public function organizationOwnershipTypes(Request $request) {
        $ownershipTypes = OrganizationOwnershipType::orderBy('sorting_index','ASC')->paginate($request->per_page ?? 15);
        return OrganizationOwnershipTypesResource::collection($ownershipTypes);
    }

    public function industryTypes(Request $request) {
        $industryTypes = IndustryType::orderBy('sorting_index','ASC')->paginate($request->per_page ?? 15);
        return IndustryTypesResource::collection($industryTypes);
    }

    public function productionTypes(Request $request) {
        $productionTypes = ProductionTypes::orderBy('sorting_index','ASC')->paginate($request->per_page ?? 15);
        return ProductionTypesResource::collection($productionTypes);
    }

    public function landOwnerships(Request $request) {
        $landOwnerships = LandOwnership::orderBy('sorting_index','ASC')->paginate($request->per_page ?? 15);
        return LandOwnershipResource::collection($landOwnerships);
    }
}
