<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\ApiResponseController;
use App\Http\Requests\Forms\UpdateFormFinancialInfoRequest;
use App\Models\FinancialInformation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Requests\Forms\StoreFormFinancialInfoRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Forms\FormsFinancialInfoResource;

class FormFinancialInformationController extends ApiResponseController
{
    public function index(Request $request)
    {

        $data = FinancialInformation::paginate($request->per_page ?? 15);
        return FormsFinancialInfoResource::collection($data);
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
    public function store(StoreFormFinancialInfoRequest $request)
    {
        try {
            DB::beginTransaction();
            FinancialInformation::updateOrCreate(
                ['organization_id' => auth()->user()->organization->id],
                [
                    'tax_indentification_no' => $request->tax_indentification_no,
                    'vat_registration_no' => $request->vat_registration_no,
                    'bank_name' => $request->bank_name,
                    'bank_branch' => $request->bank_branch,
                ]
            );
            DB::commit();
            return $this->successResponse(
                message: 'Financial Information Add Successfully'
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
            $financialInformation = FinancialInformation::query()->where(["organization_id" => auth()->user()->organization->id])->firstOrFail();
            return new FormsFinancialInfoResource($financialInformation);
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
    public function update(UpdateFormFinancialInfoRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $data = FinancialInformation::where('organization_id', auth()->user()->organization->id)->firstOrFail();
            $data->update([
                'organization_id' => auth()->user()->organization->id,
                'tax_indentification_no' => $request->tax_indentification_no,
                'vat_registration_no' => $request->vat_registration_no,
                'bank_name' => $request->bank_name,
                'bank_branch' => $request->bank_branch,
            ]);

            DB::commit();
            return $this->successResponse(
                message: 'Financial Information updated successfully'
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
