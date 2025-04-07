<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\ApiResponseController;
use App\Models\ApplianceAndBurnerDetails;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Requests\Forms\StoreApplianceBurnerRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Forms\UpdateApplianceBurnerRequest;
use App\Http\Resources\Forms\ApplianceBurnerResource;

class ApplianceBurnerController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = ApplianceAndBurnerDetails::paginate($request->per_page ?? 15);
        return ApplianceBurnerResource::collection($data);
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
    public function store(StoreApplianceBurnerRequest $request)
    {
        try {
            DB::beginTransaction();
            foreach ($request->applianceBurner as $data) {
                ApplianceAndBurnerDetails::create([
                    'organization_id' => auth()->user()->organization->id,
                    'appliance_name' => $data['appliance_name'],
                    'appliance_size' => $data['appliance_size'],
                    'appliance_production_capacity' => $data['appliance_production_capacity'],
                    'burner_type' => $data['burner_type'],
                    'burner_count' => $data['burner_count'],
                    'burner_capacity' => $data['burner_capacity'],
                    'total_load' => $data['total_load'],
                    'comments' => $data['comments'] ?? null,
                ]);
            }
            DB::commit();
            return $this->successResponse(
                message: 'Appliance And Burner Details Add successfully'
            );
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
            $applianceAndBurner = ApplianceAndBurnerDetails::query()->where(["organization_id" => auth()->user()->organization->id])->get();
            return ApplianceBurnerResource::collection($applianceAndBurner);
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
    public function update(UpdateApplianceBurnerRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            foreach ($request->applianceBurner as $data) {
                if (isset($data['id'])) {
                    ApplianceAndBurnerDetails::query()->where([
                        "id" => $data['id'],
                        "organization_id" => auth()->user()->organization->id,
                    ])->firstOrFail()->update([
                        'appliance_name' => $data['appliance_name'],
                        'appliance_size' => $data['appliance_size'],
                        'appliance_production_capacity' => $data['appliance_production_capacity'],
                        'burner_type' => $data['burner_type'],
                        'burner_count' => $data['burner_count'],
                        'burner_capacity' => $data['burner_capacity'],
                        'total_load' => $data['total_load'],
                        'comments' => $data['comments'] ?? null,
                    ]);
                } else if ($id == auth()->user()->organization->id) {
                    ApplianceAndBurnerDetails::create([
                        'organization_id' => $id,
                        'appliance_name' => $data['appliance_name'],
                        'appliance_size' => $data['appliance_size'],
                        'appliance_production_capacity' => $data['appliance_production_capacity'],
                        'burner_type' => $data['burner_type'],
                        'burner_count' => $data['burner_count'],
                        'burner_capacity' => $data['burner_capacity'],
                        'total_load' => $data['total_load'],
                        'comments' => $data['comments'] ?? null,
                    ]);
                }
            }

            DB::commit();
            return $this->successResponse(
                message: 'Appliance And Burner Details updated successfully'
            );
        } catch (ModelNotFoundException $e) {
            return $this->dataNotFoundResponse();
        } catch (\Exception $exception) {
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
        try {
            DB::beginTransaction();
            $data = ApplianceAndBurnerDetails::query()->where([
                "id" => $id,
            ])->firstOrFail();
            $data->delete();
            DB::commit();
            return $this->successResponse(
                message: 'Appliance And Burner Details Delete successfully'
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
}
