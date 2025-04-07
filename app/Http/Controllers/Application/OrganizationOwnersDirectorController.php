<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\ApiResponseController;
use App\Http\Requests\Forms\UpdateOrganizationOwnerDirectorRequest;
use App\Http\Resources\Forms\OrganizationOwnersDirectorResource;
use App\Models\OrganizationOwnersDirector;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\Forms\StoreOrganizationOwnerDirectorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrganizationOwnersDirectorController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = OrganizationOwnersDirector::paginate($request->per_page ?? 15);
        return OrganizationOwnersDirectorResource::collection($data);
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
    public function store(StoreOrganizationOwnerDirectorRequest $request)
    {
        try {
            DB::beginTransaction();
            foreach ($request->ownerAndDirectors as $data) {
                OrganizationOwnersDirector::create([
                    'name' => $data['name'],
                    'organization_id' => auth()->user()->organization->id,
                    'father_or_husband_name' => $data['father_or_husband_name'],
                    'present_address' => $data['present_address'],
                    'phone_number' => $data['phone_number'],
                    'email' => $data['email'],
                    'designation' => $data['designation'],
                    'relation_with_other_org' => $data['relation_with_other_org'],
                ]);
            }
            DB::commit();
            return $this->successResponse(
                message: 'Organization Owners & Directors created successfully'
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
            $ownersDirector = OrganizationOwnersDirector::query()->where(["organization_id" => auth()->user()->organization->id])->get();
            return OrganizationOwnersDirectorResource::collection($ownersDirector);
        }catch (ModelNotFoundException $e) {
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
    public function update(UpdateOrganizationOwnerDirectorRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            foreach ($request->ownerAndDirectors as $data) {
                if (isset($data['id'])) {
                    OrganizationOwnersDirector::query()->where([
                        "id" => $data['id'],
                        "organization_id" => auth()->user()->organization->id,
                    ])->firstOrFail()->update([
                        'name' => $data['name'],
                        'father_or_husband_name' => $data['father_or_husband_name'],
                        'present_address' => $data['present_address'],
                        'phone_number' => $data['phone_number'],
                        'email' => $data['email'],
                        'designation' => $data['designation'],
                        'relation_with_other_org' => $data['relation_with_other_org'],
                    ]);
                } else if($id == auth()->user()->organization->id) {
                    OrganizationOwnersDirector::create([
                        'organization_id' => $id,
                        'name' => $data['name'],
                        'father_or_husband_name' => $data['father_or_husband_name'],
                        'present_address' => $data['present_address'],
                        'phone_number' => $data['phone_number'],
                        'email' => $data['email'],
                        'designation' => $data['designation'],
                        'relation_with_other_org' => $data['relation_with_other_org'],
                    ]);
                }
            }

            DB::commit();
            return $this->successResponse(
                message: 'Organization Owners & Directors updated successfully'
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
            $organization = OrganizationOwnersDirector::query()->where([
                "id" => $id,
            ])->firstOrFail();
            $organization->delete();
            DB::commit();
            return $this->successResponse(
                message: 'Organization Owners & Directors Delete successfully'
            );
        }catch (ModelNotFoundException $e) {
            return $this->dataNotFoundResponse();
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse(
                message: $exception->getMessage(),
            );
        }
    }


}
