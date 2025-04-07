<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePaymentGetawayRequest;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\SettingsResource;
use App\Http\Requests\StoreSettingsRequest;
use App\Http\Requests\UpdateSettingsRequest;
use mysql_xdevapi\Exception;

class SettingsController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = Settings::paginate(request()->per_page ?? 10);
        return SettingsResource::collection($settings);

    }


    public function paymentGetawayUpdate(UpdatePaymentGetawayRequest $request)
    {
        $paymentGetaway = Settings::findOrFail(2);
        $dataRequest = [
            'password' => $request->password,
            'user_name' => $request->user_name,
            'credit_amount' => $request->credit_amount,
            'credit_account' => $request->credit_account
        ];
        try {
            DB::beginTransaction();
            $paymentGetaway->data = json_encode($dataRequest);
            $paymentGetaway->save();
            DB::commit();
            return new SettingsResource($paymentGetaway);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['message' => $exception->getMessage()], 500);
        }
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
    public function store(StoreSettingsRequest $request)
    {
        try {
            DB::beginTransaction();

            $settings = Settings::create([
                'organization_id' => $request->organization_id,
                'module' => $request->module,
                'name' => $request->name,
                'data' => json_encode($request->data),
            ]);
            DB::commit();
            return new SettingsResource($settings);

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
        $settings = Settings::find($id);
        return new SettingsResource($settings);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $settings = Settings::find($id);
        return new SettingsResource($settings);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSettingsRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $settings = Settings::find($id);

            $settings->update([
                'organization_id' => $request->organization_id,
                'module' => $request->module,
                'name' => $request->name,
                'data' => json_encode($request->data),
            ]);
            DB::commit();
            return new SettingsResource($settings);

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
        try {
            $settings = Settings::find($id);
            if ($settings) {
                $settings->delete();
                return response()->json(['message' => 'Settings deleted successfully']);
            } else {
                return response()->json(['message' => 'Settings not found']);
            }
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error deleting department: ' . $e->getMessage()], 500);
        }
    }
}

