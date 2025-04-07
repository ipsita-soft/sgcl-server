<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiResponseController;
use App\Http\Requests\Forms\UpdateFormRequest;
use App\Http\Requests\UpdateOrganizationRequest;
use App\Http\Resources\ApplicationFormViewResource;
use App\Http\Resources\Forms\FormShowResource;
use App\Http\Resources\Forms\FormsResource;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FormController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    public function applicationFormView()
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
            ])->where('id', auth()->user()->organization->id)->firstOrFail();
            return new  ApplicationFormViewResource($organization);
        } catch (ModelNotFoundException $e) {
            return $this->dataNotFoundResponse();
        } catch (\Exception $exception) {
            return $this->errorResponse(
                message: $exception->getMessage(),
            );
        }
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $organization = Organization::where('user_id', auth()->user()->id)->first();
        return (new FormsResource($organization));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(UpdateFormRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $organization = Organization::where('id', $id)->where('user_id', auth()->user()?->id)->firstOrFail();
            $organization->update([
                'organization_category_id' => $request->input('organization_category_id'),
                'application_date' => $request->input('application_date'),
                'factory_name' => $request->input('factory_name'),
                'factory_address' => $request->input('factory_address'),
                'factory_telephone' => $request->input('factory_telephone'),
                'main_office_address' => $request->input('main_office_address'),
                'main_office_telephone' => $request->input('main_office_telephone'),
                'billing_address' => $request->input('billing_address'),
                'billing_telephone' => $request->input('billing_telephone'),
                'mobile' => auth()->user()?->phone,
                'email' => auth()->user()?->email,
                'national_id' => $request->input('national_id'),
                'tax_identification_no' => $request->input('tax_identification_no'),
                'gis_location' => $request->input('gis_location'),
                'organization_ownership_type_id' => $request->input('organization_ownership_type_id'),
                'industry_type_id' => $request->input('industry_type_id'),
                'trade_license_no' => $request->input('trade_license_no'),
                'license_expiry_date' => $request->input('license_expiry_date'),
            ]);

            DB::commit();

            return (new FormsResource($organization))->additional([
                'message' => 'Organization Create and update Success',
            ]);

        } catch (ModelNotFoundException $e) {
            return $this->dataNotFoundResponse();
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
