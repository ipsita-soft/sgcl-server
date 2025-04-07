<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\ApiResponseController;
use App\Models\ApplianceAndBurnerInfo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Requests\Forms\StoreApplianceAndBurnerInfoRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Forms\UpdateApplianceAndBurnerInfoRequest;
use App\Http\Resources\Forms\ApplianceAndBurnerInfoResource;

class FormApplianceAndBurnerInfo extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = ApplianceAndBurnerInfo::paginate($request->per_page ?? 15);
        return ApplianceAndBurnerInfoResource::collection($data);
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
    public function store(StoreApplianceAndBurnerInfoRequest $request)
    {

        try {
            DB::beginTransaction();
            ApplianceAndBurnerInfo::updateOrCreate([
                'organization_id' => auth()->user()->organization->id,
            ],[
                'gas_usage_hours' => $request->gas_usage_hours,
                'gas_usage_unit' => $request->gas_usage_unit,
                'expected_gas_parssure' => $request->expected_gas_parssure,
            ]);
            DB::commit();
            return $this->successResponse(
                message: 'Appliance And Burner Info Add Successfully'
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
            $applianceAndBurnerInfo = ApplianceAndBurnerInfo::query()->where(["organization_id" => auth()->user()->organization->id])->firstOrFail();
            return new ApplianceAndBurnerInfoResource($applianceAndBurnerInfo);
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
    public function update(UpdateApplianceAndBurnerInfoRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $ApplianceAndBurnerInfo = ApplianceAndBurnerInfo::where('organization_id', auth()->user()->organization->id)->firstOrFail();
            $ApplianceAndBurnerInfo->update([
                'organization_id' => auth()->user()->organization->id,
                'gas_usage_hours' => $request->gas_usage_hours,
                'gas_usage_unit' => $request->gas_usage_unit,
                'expected_gas_parssure' => $request->expected_gas_parssure,
            ]);

            DB::commit();
            return $this->successResponse(
                message: 'Appliance And Burner Info updated successfully'
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
