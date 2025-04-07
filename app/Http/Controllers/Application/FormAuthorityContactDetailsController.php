<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Forms\StoreAuthorityContactDetailsRequest;
use App\Http\Requests\Forms\UpdateAuthorityContactDetailsRequest;
use App\Http\Resources\Forms\AuthorityContactDetailsResource;
use App\Models\AuthorityContactDetails;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormAuthorityContactDetailsController extends ApiResponseController
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = AuthorityContactDetails::paginate($request->per_page ?? 15);
        return AuthorityContactDetailsResource::collection($data);
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
    public function store(StoreAuthorityContactDetailsRequest $request)
    {
        try {
            DB::beginTransaction();
            foreach ($request->authorityContactDetails as $data) {
                AuthorityContactDetails::create([
                    'organization_id' => auth()->user()->organization->id,
                    'name' => $data['name'],
                    'designation' => $data['designation'],
                    'national_id' => $data['national_id'],
                    'mobile' => $data['mobile'],
                    'email' => $data['email'],
                ]);
            }
            DB::commit();
            return $this->successResponse(
                message: 'Authority Contact Details created successfully'
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
            $authorityContactDetails = AuthorityContactDetails::query()->where(["organization_id" => auth()->user()->organization->id])->get();
            return AuthorityContactDetailsResource::collection($authorityContactDetails);
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
    public function update(UpdateAuthorityContactDetailsRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            foreach ($request->authorityContactDetails as $data) {
                if (isset($data['id'])) {
                    AuthorityContactDetails::query()->where([
                        "id" => $data['id'],
                        "organization_id" => auth()->user()->organization->id,
                    ])->firstOrFail()->update([
                        'name' => $data['name'],
                        'designation' => $data['designation'],
                        'national_id' => $data['national_id'],
                        'mobile' => $data['mobile'],
                        'email' => $data['email'],
                    ]);
                } else if ($id == auth()->user()->organization->id) {
                    AuthorityContactDetails::create([
                        'organization_id' => $id,
                        'name' => $data['name'],
                        'designation' => $data['designation'],
                        'national_id' => $data['national_id'],
                        'mobile' => $data['mobile'],
                        'email' => $data['email'],
                    ]);
                }
            }

            DB::commit();
            return $this->successResponse(
                message: 'Authority Contact Details updated successfully'
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
            $data = AuthorityContactDetails::query()->where([
                "id" => $id,
            ])->firstOrFail();
            $data->delete();
            DB::commit();
            return $this->successResponse(
                message: 'Authority Contact Details Delete successfully'
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
