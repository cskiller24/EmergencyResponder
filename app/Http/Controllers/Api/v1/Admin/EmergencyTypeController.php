<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmergencyTypeRequest;
use App\Http\Resources\EmergencyTypeResource;
use App\Models\EmergencyType;
use Illuminate\Validation\ValidationException;

class EmergencyTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', EmergencyType::class);

        return EmergencyTypeResource::collection(
            EmergencyType::query()->paginate(validatePerPage(10))
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmergencyTypeRequest $request)
    {
        $emergencyType = EmergencyType::query()->create($request->validated());

        return EmergencyTypeRequest::make($emergencyType);
    }

    /**
     * Display the specified resource.
     */
    public function show(EmergencyType $emergencyType)
    {
        $emergencyType->load(['responders', 'submissions']);

        return EmergencyTypeResource::make($emergencyType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmergencyTypeRequest $request, EmergencyType $emergencyType)
    {
        $emergencyType->update($request->validated());

        return EmergencyTypeResource::make($emergencyType);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmergencyType $emergencyType)
    {
        $this->authorize('delete', EmergencyType::class);

        if ($emergencyType->submissions()->exists() || $emergencyType->responders()->exists()) {
            throw ValidationException::withMessages(['error' => 'Emergency type is being used']);
        }

        $emergencyType->delete();

        return response()->noContent();
    }
}
