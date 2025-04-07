<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Forms\StoreAttachmentsRequest;
use App\Http\Requests\Forms\UpdateAttachmentsRequest;
use App\Http\Resources\Forms\AttachmentsResource;
use App\Models\Attachment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FormAttachmentsController extends ApiResponseController
{

    public function index(Request $request)
    {


        $data = Attachment::paginate($request->per_page ?? 15);
        return AttachmentsResource::collection($data);
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
    public function store(StoreAttachmentsRequest $request)
    {
        try {
            DB::beginTransaction();

            $storedFiles = [];
            $yearDirectory = '/attachments/' . date('Y') . '/';

            foreach ($request->allFiles() as $key => $file) {
                $storedFileName = $key . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                Storage::disk('public')->putFileAs($yearDirectory, $file, $storedFileName);
                $storedFiles[$key] = 'storage' . $yearDirectory . $storedFileName; // Store file path
            }

            $attachment = new Attachment();
            $attachment->organization_id = $request->organization_id;
            $attachment->passport_size_photo_file = $storedFiles['passport_size_photo_file'] ?? null;
            $attachment->trade_license = $storedFiles['trade_license'] ?? null;
            $attachment->tin_certificates = $storedFiles['tin_certificates'] ?? null;
            $attachment->certificate_of_incorporation = $storedFiles['certificate_of_incorporation'] ?? null;
            $attachment->proof_document = $storedFiles['proof_document'] ?? null;
            $attachment->rent_agreement = $storedFiles['rent_agreement'] ?? null;
            $attachment->factorys_layout_plan = $storedFiles['factorys_layout_plan'] ?? null;
            $attachment->proposed_pipeline_design = $storedFiles['proposed_pipeline_design'] ?? null;
            $attachment->technical_catalog = $storedFiles['technical_catalog'] ?? null;
            $attachment->signature = $storedFiles['signature'] ?? null;
            $attachment->nid = $storedFiles['nid'] ?? null;
            $attachment->certificate_of_registration_industry = $storedFiles['certificate_of_registration_industry'] ?? null;
            $attachment->noc_of_dept_environment = $storedFiles['noc_of_dept_environment'] ?? null;
            $attachment->others = $storedFiles['others'] ?? null;

            $attachment->save();

            DB::commit();
            return $this->successResponse(
                message: 'Attachment File Add Successfully'
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
            $attachment = Attachment::query()->where(["organization_id" => auth()->user()->organization->id])->firstOrFail();
            return new AttachmentsResource($attachment);
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
    public function update(UpdateAttachmentsRequest $request, $id)
    {

        try {
            DB::beginTransaction();

            $attachment =  Attachment::where('organization_id',auth()->user()->organization->id)->firstOrFail();

            $storedFiles = [];
            $yearDirectory = 'attachments/' . date('Y') . '/';

            foreach ($request->allFiles() as $key => $file) {
                $storedFileName = $key . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                Storage::disk('public')->putFileAs($yearDirectory, $file, $storedFileName);
                $storedFiles[$key] = 'storage/' . $yearDirectory . $storedFileName; // Store file path

                // If new file is uploaded, delete the old file
                if ($file && $attachment->{$key}) {
                    Storage::disk('public')->delete($attachment->{$key});
                }
            }

            // Update attachment properties
            $attachment->organization_id = $request->organization_id;
            $attachment->passport_size_photo_file = $storedFiles['passport_size_photo_file'] ?? $attachment->passport_size_photo_file;
            $attachment->trade_license = $storedFiles['trade_license'] ?? $attachment->trade_license;
            $attachment->tin_certificates = $storedFiles['tin_certificates'] ?? $attachment->tin_certificates;
            $attachment->certificate_of_incorporation = $storedFiles['certificate_of_incorporation'] ?? $attachment->certificate_of_incorporation;
            $attachment->proof_document = $storedFiles['proof_document'] ?? $attachment->proof_document;
            $attachment->rent_agreement = $storedFiles['rent_agreement'] ?? $attachment->rent_agreement;
            $attachment->factorys_layout_plan = $storedFiles['factorys_layout_plan'] ?? $attachment->factorys_layout_plan;
            $attachment->proposed_pipeline_design = $storedFiles['proposed_pipeline_design'] ?? $attachment->proposed_pipeline_design;
            $attachment->technical_catalog = $storedFiles['technical_catalog'] ?? $attachment->technical_catalog;
            $attachment->signature = $storedFiles['signature'] ?? $attachment->signature;
            $attachment->nid = $storedFiles['nid'] ?? $attachment->nid;
            $attachment->certificate_of_registration_industry = $storedFiles['certificate_of_registration_industry'] ?? $attachment->certificate_of_registration_industry;
            $attachment->noc_of_dept_environment = $storedFiles['noc_of_dept_environment'] ?? $attachment->noc_of_dept_environment;
            $attachment->others = $storedFiles['others'] ?? $attachment->others;


            // Update other properties similarly...

            $attachment->save();

            DB::commit();
            return $this->successResponse(
                message: 'Attachment File Updated Successfully'
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
