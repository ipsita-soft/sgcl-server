<?php

namespace App\Services\Activity;

use Exception;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class ActivityLogService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function create_log($data){
        try {
            DB::beginTransaction();

            $log = ActivityLog::create([
                'user_id' => auth()->id(),
                'note' => $data->note,
                'loggable_type' => $data->loggable_type,
                'loggable_id' => $data->loggable_id,
                'source_type' => $data->source_type,
                'source_id' => $data->source_id,
                'new_data' => json_encode($data->new_data),
                'old_data' => json_encode($data->old_data),
                'module' => $data->module,
            ]);

            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new Exception($exception->getMessage());
        }
    }
    
}
