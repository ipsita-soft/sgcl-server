<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Forms\StoreFormManufacturingRequest;
use App\Http\Requests\Forms\updateFormManufacturingRequest;
use App\Http\Resources\Forms\FormsManufacturingResource;
use App\Models\ManufacturingData;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class FormManufacturingData extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = ManufacturingData::paginate($request->per_page ?? 15);
        return FormsManufacturingResource::collection($data);
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
    public function store(StoreFormManufacturingRequest $request)
    {
        try {
            DB::beginTransaction();
            ManufacturingData::create([
                'organization_id' => $request->organization_id,
                'production_type_id' => $request->production_type_id,
                'factory_starting_time' => $request->factory_starting_time,
                'factory_ending_time' => $request->factory_ending_time,
            ]);
            DB::commit();
            return $this->successResponse(
                message: 'Manufacturing Data Add Successfully'
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
            $manufacturingData = ManufacturingData::query()->where(["organization_id" => auth()->user()->organization->id])->firstOrFail();
            return new FormsManufacturingResource($manufacturingData);
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
    public function update(updateFormManufacturingRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $data = ManufacturingData::where('organization_id', auth()->user()->organization->id)->firstOrFail();
            $data->update([
                'organization_id' => $request->organization_id,
                'production_type_id' => $request->production_type_id,
                'factory_starting_time' => $request->factory_starting_time,
                'factory_ending_time' => $request->factory_ending_time,
            ]);

            DB::commit();
            return $this->successResponse(
                message: 'Manufacturing Data updated successfully'
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
