<?php

namespace App\Http\Controllers;

use App\Http\Requests\Application;
use App\Http\Resources\Forms\IngredientsInfoForProductionResource;
use App\Http\Resources\UserResource;
use App\Models\IngredientsInfoForProduction;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplicationController extends ApiResponseController
{
    public function index(Request $request)
    {
        $data = User::where('application_fee', 'Paid')
        ->whereHas('role', function ($query) {
            $query->where('name', 'applicant');
        })->paginate($request->per_page ?? 15);
        return UserResource::collection($data);

    }

    public function member(Request $request)
    {
        $data = User::where('application_fee', 'Paid')
        ->whereHas('role', function ($query) {
            $query->where('name', 'member');
        })->paginate($request->per_page ?? 15);
        return UserResource::collection($data);

    }

    public function show($id)
    {
        try {
            $data = User::where('application_fee', '=', null)->where('id', $id)
                ->whereHas('role', function ($query) {
                    $query->where('name', 'applicant');
                })
                ->firstOrFail();
            return new UserResource($data);
        } catch (ModelNotFoundException $e) {
            return $this->dataNotFoundResponse();
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse(
                message: $exception->getMessage(),
            );
        }

    }

    public function memberShow($id)
    {
        try {
            $data = User::where('application_fee', '=', 'Paid')->where('id', $id)
                ->whereHas('role', function ($query) {
                    $query->where('name', 'member');
                })
                ->firstOrFail();
            return new UserResource($data);
        } catch (ModelNotFoundException $e) {
            return $this->dataNotFoundResponse();
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse(
                message: $exception->getMessage(),
            );
        }

    }

    public function update(Application $request, $id)
    {
        try {
            DB::beginTransaction();
            $data = User::where('application_fee', 'Paid')->where('id', $id)
                ->whereHas('role', function ($query) {
                    $query->where('name', 'applicant');
                })->firstOrFail();

            $data->role_id = Role::where('name', 'member')->value('id');
            $data->save();
            DB::commit();
            return (new UserResource($data))->additional([
                'message' => 'Payments Status update successful',
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->dataNotFoundResponse();
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse(
                message: $exception->getMessage(),
            );
        }
    }

    public function memberUpdate(Application $request, $id)
    {
        try {
            $data = User::where('application_fee', '=', 'Paid')->where('id', $id)
                ->whereHas('role', function ($query) {
                    $query->where('name', 'member');
                })
                ->firstOrFail();
            DB::beginTransaction();

            $data->update([
                'application_fee' => $request->application_fee,
                'role_id' => Role::where('name', 'applicant')->value('id'),
            ]);
            DB::commit();
            return (new UserResource($data))->additional([
                'message' => 'Payments Status update successful',
            ]);

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
