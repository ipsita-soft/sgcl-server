<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApplicationFormViewResource;
use App\Models\Organization;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Http\Resources\OrganizationResource;
use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\UpdateOrganizationRequest;
use App\Http\Controllers\ApiResponseController;

class OrganizationController extends ApiResponseController
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $organizations = Organization::with(['user', 'organizationCategory', 'ownershipType', 'industryType'])->when($request->organization_category_id, function ($query, $category_id) {
            $query->where('organization_category_id', 'LIKE', '%' . $category_id . '%');
        })->when($request->application_date, function ($query, $application_date) {
            $query->where('application_date', 'LIKE', '%' . $application_date . '%');
        })->when($request->factory_name, function ($query, $factory_name) {
            $query->where('factory_name', 'LIKE', '%' . $factory_name . '%');
        })->when($request->mobile, function ($query, $mobile) {
            $query->where('mobile', 'LIKE', '%' . $mobile . '%');
        })->when($request->email, function ($query, $email) {
            $query->where('email', 'LIKE', '%' . $email . '%');
        })->when($request->industry_type_id, function ($query, $industry_type_id) {
            $query->where('industry_type_id', 'LIKE', '%' . $industry_type_id . '%');
        })->whereHas('user', function ($query) {
            $query->where('application_fee', '=', 'Paid');
        })->paginate(request()->per_page ?? 10);

        return OrganizationResource::collection($organizations);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrganizationRequest $request)
    {
        try {
            DB::beginTransaction();
            Organization::where('user_id', auth()->user()->id)->findOrFirst();
            $organization = Organization::updated([
                'name' => $request->name,
                'parent_id' => $request->parent_id,
            ]);
            DB::commit();
            return new OrganizationResource($organization);

        } catch (ModelNotFoundException $e) {
            return $this->dataNotFoundResponse();
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse(
                message: $exception->getMessage(),
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $organization = Organization::with([
                'organizationCategory',
                'ownershipType',
                'industryType',
                'user',
                'applianceBurnerDetails',
                'applianceBurnerInfo',
                'attachment',
                'authorityContactDetails',
                'expectedGasNeed',
                'financialInformation',
                'ingredientsInfoForProduction',
                'location',
                'manufacturingData',
                'organizationOwnersDirector',
            ])->where('id', $id)->firstOrFail();
            return new  ApplicationFormViewResource($organization);
        } catch (ModelNotFoundException $exception) {
            return $this->dataNotFoundResponse();
        } catch (\Exception $exception) {
            return $this->errorResponse(
                message: $exception->getMessage(),
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Organization $organization)
    {
        return new OrganizationResource($organization);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // return $request->headers();
        try {
            DB::beginTransaction();
            $organization = Organization::findOrFail($id);
            $organization->update([
                'name' => $request->name,
                'parent_id' => $request->parent_id
            ]);
            DB::commit();
            return new OrganizationResource($organization);
        } catch (ModelNotFoundException $e) {
            return $this->dataNotFoundResponse();
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organization $organization)
    {
        try {
            if ($organization) {
                $organization->delete();
                return response()->json(['message' => 'Organization deleted successfully']);
            } else {
                return response()->json(['message' => 'Organization not found']);
            }
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error deleting organization: ' . $e->getMessage()], 500);
        }
    }
}
