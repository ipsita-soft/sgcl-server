<?php

namespace App\Http\Controllers;

use App\Http\Resources\FeeRemindersResource;
use App\Jobs\SendRemindersMessageJob;
use App\Jobs\UpdateRemindersMessageJob;
use App\Models\FeeReminders;
use App\Http\Requests\StoreFeeRemindersRequest;
use App\Http\Requests\UpdateFeeRemindersRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class FeeRemindersController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userId = $request->send_to;

        if(auth()->user()?->role->name == 'member'){
            $userId = auth()->user()?->id;
        }

        $feeReminders = FeeReminders::with(['sendBy','sendTo'])->when($userId, function ($query, $userId) {
            return $query->where('send_to', $userId);
        })->when($request->message, function($query, $message){
            $query->where('message','LIKE', '%'. $message . '%');
        })->when($request->amount, function($query, $amount){
            $query->where('amount','LIKE', '%'. $amount . '%');
        })->when($request->date, function($query, $date){
            $query->where('date',$date);
        })->when($request->send_to, function($query, $send_to){
            $query->where('send_to',$send_to);
        })->when($request->created_at, function($query, $created_at){
            $query->whereDate('created_at',$created_at);
        })->orderBy('created_at', 'DESC')->paginate($request->per_page ?? 15);

        return FeeRemindersResource::collection($feeReminders);
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
    public function store(StoreFeeRemindersRequest $request)
    {
        try {
            DB::beginTransaction();
            $validated = $request->validated();

            foreach ($validated['remindersData'] as $data) {
                $jobData = array_merge($data, [
                    'message' => $validated['message'],
                    'amount' => $validated['amount'],
                    'date' => $validated['date'],
                    'sender' => auth()->user()->id,
                ]);

                dispatch(new SendRemindersMessageJob($jobData));
            }
            Db::commit();
            return $this->successResponse(
                message: 'Fee Reminder Send successfully'
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
            $user = auth()->user();
            $userId = $user->id;
            $feeReminders = FeeReminders::with(['sendBy','sendTo'])->when($user->role->name !== 'Admin' && $user->role->name !== 'Super Admin', function ($query) use ($userId) {
                return $query->where('send_to', $userId);
            })->where('id', $id)->firstOrFail();
            return new FeeRemindersResource($feeReminders);
        } catch (ModelNotFoundException $e) {
            return $this->dataNotFoundResponse();
        } catch (\Exception $exception) {
            return $this->errorResponse(
                message: $exception->getMessage(),
            );
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FeeReminders $feeReminders)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFeeRemindersRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            dispatch(new UpdateRemindersMessageJob($id, $validated));

            Db::commit();
            return $this->successResponse(
                message: 'Fee Reminder  update  successfully'
            );
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
    public function destroy(FeeReminders $feeReminders)
    {
        //
    }
}
