<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmergencyTypeRequest;
use App\Models\EmergencyType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class EmergencyTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $emergencyTypes = EmergencyType::all();

        return view('admin.emergency-types', compact('emergencyTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmergencyTypeRequest $request): RedirectResponse
    {
        $this->authorize('create', EmergencyType::class);

        EmergencyType::query()->create($request->validated());

        \toastr()->success('Added emergency type successfully!');

        return redirect()->route('admin.emergency-types.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(EmergencyType $emergencyType): View
    {
        $emergencyType->load(['submissions', 'responders']);

        return view('admin.emegency-types-show', compact('emergencyType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmergencyTypeRequest $request, EmergencyType $emergencyType): RedirectResponse
    {
        $this->authorize('update', EmergencyType::class);

        $emergencyType->update($request->validated());

        \toastr()->success('Emergency type updated successfully');

        return redirect()->route('admin.emergency-types.show', $emergencyType->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmergencyType $emergencyType): RedirectResponse
    {
        $this->authorize('delete', EmergencyType::class);

        if ($emergencyType->submissions()->exists() || $emergencyType->responders()->exists()) {
            throw ValidationException::withMessages(['error' => 'Emergency type is being used']);
        }

        $emergencyType->delete();

        \toastr()->success('Emergency type successfully deleted');

        return redirect()->route('admin.emergency-types.index');
    }
}
