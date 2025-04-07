<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Http\Resources\LocationResource;
use App\Http\Requests\StoreLocationRequest;
use App\Http\Requests\UpdateLocationRequest;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locations = Location::with('organization','parentLocation','childLocations')->paginate(request()->per_page ?? 10);
        return LocationResource::collection($locations);
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
    public function store(StoreLocationRequest $request)
    {
        try {
            DB::beginTransaction();

            $location = Location::create([
                'name' => $request->name,
                'parent_id' => $request->parent_id,
                'organization_id' => $request->organization_id
            ]);
            DB::commit();
            return new LocationResource($location);

        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location)
    {
        $location->load('organization', 'parentLocation', 'childLocations');
        return new LocationResource($location);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Location $location)
    {
        return new LocationResource($location);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLocationRequest $request, Location $location)
    {
        try {
            DB::beginTransaction();

            $location->update([
                'name' => $request->name,
                'parent_id' => $request->parent_id,
                'organization_id' => $request->organization_id
            ]);

            DB::commit();

            return new LocationResource($location);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        try {
            if ($location) {
                $location->delete();
                return response()->json(['message' => 'Location deleted successfully']);
            } else {
                return response()->json(['message' => 'Location not found']);
            }
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error deleting location: ' . $e->getMessage()], 500);
        }
    }
}
