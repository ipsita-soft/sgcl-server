<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Http\Requests\Forms\UpdateFormLocationRequest;
use App\Http\Resources\Forms\FormsLocationResource;
use App\Models\Location;
use App\Models\Organization;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Requests\Forms\StoreFormLocationRequest;
use App\Http\Controllers\ApiResponseController;
use Illuminate\Support\Facades\DB;

class FormLocationController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $location = Location::paginate($request->per_page ?? 15);
        return FormsLocationResource::collection($location);
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
    public function store(StoreFormLocationRequest $request)
    {
        try {
            DB::beginTransaction();
            $organization = Organization::where('id', $request->organization_id)->where('user_id', auth()->user()?->id)->firstOrFail();
            Location::create([
                'organization_id' => $organization->id,
                'mouza_name' => $request->mouza_name,
                'daag_no' => $request->daag_no,
                'khotiyan_no' => $request->khotiyan_no,
                'total_land_area' => $request->total_land_area,
                'land_ownership_id' => $request->land_ownership_id,
                'land_width_feet' => $request->land_width_feet,
                'land_length_feet' => $request->land_length_feet,

                'owner_name_ifRented' => $request->owner_name_ifRented,
                'owner_address_ifRented' => $request->owner_address_ifRented,
                'lease_provider_organization_name_Ifleased' => $request->lease_provider_organization_name_Ifleased,
                'lease_provider_organization_address_if_leased' => $request->lease_provider_organization_address_if_leased,

                'any_other_customer_used_gas' => $request->any_other_customer_used_gas,

                'customer_code_no' => $request->any_other_customer_used_gas == 1 ? $request->customer_code_no : null,
                'organization_name' => $request->any_other_customer_used_gas == 1 ? $request->organization_name : null,
                'customer_name' => $request->any_other_customer_used_gas == 1 ? $request->customer_name : null,

                'connection_status' => $request->any_other_customer_used_gas == 1 ? $request->connection_status : null,
                'clearance_of_gas_bill' => $request->clearance_of_gas_bill,

                'is_organization_owner' => $request->is_organization_owner,
                'owner_partner_code' => $request->is_organization_owner == 1 ? $request->owner_partner_code : null,
                'owner_partner_name' => $request->is_organization_owner == 1 ? $request->owner_partner_name : null,
                'owner_partner_status' => $request->is_organization_owner == 1 ? $request->owner_partner_status : null,
            ]);
            DB::commit();
            return $this->successResponse(
                message: 'Organization Location Create Successfully'
            );
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
            $location = Location::query()->where(["organization_id" => auth()->user()->organization->id])->firstOrFail();
            return new FormsLocationResource($location);
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
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFormLocationRequest $request, $id)
    {

        try {
            DB::beginTransaction();
            $location = Location::where('organization_id', auth()->user()->organization->id)->firstOrFail();
            $location->update([
                'organization_id' => $id,
                'mouza_name' => $request->mouza_name,
                'daag_no' => $request->daag_no,
                'khotiyan_no' => $request->khotiyan_no,
                'total_land_area' => $request->total_land_area,
                'land_ownership_id' => $request->land_ownership_id,
                'land_width_feet' => $request->land_width_feet,
                'land_length_feet' => $request->land_length_feet,

                'owner_name_ifRented' => $request->owner_name_ifRented,
                'owner_address_ifRented' => $request->owner_address_ifRented,
                'lease_provider_organization_name_Ifleased' => $request->lease_provider_organization_name_Ifleased,
                'lease_provider_organization_address_if_leased' => $request->lease_provider_organization_address_if_leased,

                'any_other_customer_used_gas' => $request->any_other_customer_used_gas,

                'customer_code_no' => $request->any_other_customer_used_gas == 1 ? $request->customer_code_no : null,
                'organization_name' => $request->any_other_customer_used_gas == 1 ? $request->organization_name : null,
                'customer_name' => $request->any_other_customer_used_gas == 1 ? $request->customer_name : null,

                'connection_status' => $request->any_other_customer_used_gas == 1 ? $request->connection_status : null,
                'clearance_of_gas_bill' => $request->clearance_of_gas_bill,

                'is_organization_owner' => $request->is_organization_owner,
                'owner_partner_code' => $request->is_organization_owner == 1 ? $request->owner_partner_code : null,
                'owner_partner_name' => $request->is_organization_owner == 1 ? $request->owner_partner_name : null,
                'owner_partner_status' => $request->is_organization_owner == 1 ? $request->owner_partner_status : null,
            ]);

            DB::commit();
            return $this->successResponse(
                message: 'Organization Location updated successfully'
            );
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
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //

    }
}
