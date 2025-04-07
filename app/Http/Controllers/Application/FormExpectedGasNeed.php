<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\ApiResponseController;
use App\Http\Requests\Forms\StoreFormExpectedGasNeedRequest;
use App\Http\Requests\Forms\UpdateFormExpectedGasNeedRequest;
use App\Models\ExpectedGasNeed;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Forms\ExpectedGasNeedResource;

class FormExpectedGasNeed extends ApiResponseController
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = ExpectedGasNeed::paginate($request->per_page ?? 15);
        return ExpectedGasNeedResource::collection($data);
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
    public function store(StoreFormExpectedGasNeedRequest $request)
    {
        try {
            DB::beginTransaction();
            foreach ($request->expectedGasNeed as $data) {
                ExpectedGasNeed::create([
                    'organization_id' => auth()->user()->organization->id,
                    'year' => $data['year'],
                    'demand' => $data['demand'],
                    'cubic_meter' => $data['cubic_meter']
                ]);
            }
            DB::commit();
            return $this->successResponse(
                message: 'Expected Gas Need created successfully'
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
            $expectedGasNeed = ExpectedGasNeed::query()->where(["organization_id" => auth()->user()->organization->id])->get();
            return ExpectedGasNeedResource::collection($expectedGasNeed);
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
    public function update(UpdateFormExpectedGasNeedRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            foreach ($request->expectedGasNeed as $data) {
                if (isset($data['id'])) {
                    ExpectedGasNeed::query()->where([
                        "id" => $data['id'],
                        "organization_id" => auth()->user()->organization->id,
                    ])->firstOrFail()->update([
                        'year' => $data['year'],
                        'demand' => $data['demand'],
                        'cubic_meter' => $data['cubic_meter']
                    ]);
                } else if ($id == auth()->user()->organization->id) {
                    ExpectedGasNeed::create([
                        'organization_id' => $id,
                        'year' => $data['year'],
                        'demand' => $data['demand'],
                        'cubic_meter' => $data['cubic_meter']
                    ]);
                }
            }

            DB::commit();
            return $this->successResponse(
                message: 'Expected Gas Need updated successfully'
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
            $data = ExpectedGasNeed::query()->where([
                "id" => $id,
            ])->firstOrFail();
            $data->delete();
            DB::commit();
            return $this->successResponse(
                message: 'Expected Gas Need Delete successfully'
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
