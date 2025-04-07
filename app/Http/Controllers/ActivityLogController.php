<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ActivityLogResource;
use App\Http\Requests\StoreActivityLogRequest;
use App\Http\Requests\UpdateActivityLogRequest;

class ActivityLogController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activity_logs = ActivityLog::paginate(request()->per_page ?? 10);
        return response()->json(['activity_logs' => ActivityLogResource::collection($activity_logs)]);
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
    public function store(StoreActivityLogRequest $request)
    {
        try {
            DB::beginTransaction();
            DB::commit();
            $log = ActivityLog::create([
                'user_id' => $request->user_id,
                'loggable_type' => $request->loggable_type,
                'loggable_id' => $request->loggable_id,
                'source_type' => $request->source_type,
                'source_id' => $request->source_id,
                'new_data' => json_encode($request->new_data),
                'old_data' => json_encode($request->old_data),
                'module' => $request->module,
            ]);

            return new ActivityLogResource($log);
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
    public function show(ActivityLog $activityLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ActivityLog $activityLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateActivityLogRequest $request, ActivityLog $activityLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ActivityLog $activityLog)
    {
        //
    }
}
