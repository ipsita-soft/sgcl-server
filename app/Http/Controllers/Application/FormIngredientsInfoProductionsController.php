<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\ApiResponseController;
use App\Http\Requests\Forms\UpdateIngredientsInfoProductionRequest;
use App\Http\Resources\Forms\IngredientsInfoForProductionResource;
use App\Models\IngredientsInfoForProduction;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Requests\Forms\StoreIngredientsInfoProductionRequest;
use Illuminate\Support\Facades\DB;

class FormIngredientsInfoProductionsController extends ApiResponseController
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = IngredientsInfoForProduction::paginate($request->per_page ?? 15);
        return IngredientsInfoForProductionResource::collection($data);
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
    public function store(StoreIngredientsInfoProductionRequest $request)
    {
        try {
            DB::beginTransaction();
            foreach ($request->ingredientsInfoProduction as $data) {
                IngredientsInfoForProduction::create([
                    'organization_id' => auth()->user()->organization->id,
                    'goods_name' => $data['goods_name'],
                    'yearly_production' => $data['yearly_production'],
                    'where_sold' => $data['where_sold']
                ]);
            }
            DB::commit();
            return $this->successResponse(
                message: 'Ingredients Info Production created successfully'
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
            $ingredientsInfoForProduction = IngredientsInfoForProduction::query()->where(["organization_id" => auth()->user()->organization->id])->get();
            return IngredientsInfoForProductionResource::collection($ingredientsInfoForProduction);
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
    public function update(UpdateIngredientsInfoProductionRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            foreach ($request->ingredientsInfoProduction as $data) {
                if (isset($data['id'])) {
                    IngredientsInfoForProduction::query()->where([
                        "id" => $data['id'],
                        "organization_id" => auth()->user()->organization->id,
                    ])->firstOrFail()->update([
                        'goods_name' => $data['goods_name'],
                        'yearly_production' => $data['yearly_production'],
                        'where_sold' => $data['where_sold']
                    ]);
                } else if ($id == auth()->user()->organization->id) {
                    IngredientsInfoForProduction::create([
                        'organization_id' => $id,
                        'goods_name' => $data['goods_name'],
                        'yearly_production' => $data['yearly_production'],
                        'where_sold' => $data['where_sold']
                    ]);
                }
            }

            DB::commit();
            return $this->successResponse(
                message: 'Ingredients Info Production updated successfully'
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
            $data = IngredientsInfoForProduction::query()->where([
                "id" => $id,
            ])->firstOrFail();
            $data->delete();
            DB::commit();
            return $this->successResponse(
                message: 'Ingredients Info Production Delete successfully'
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
