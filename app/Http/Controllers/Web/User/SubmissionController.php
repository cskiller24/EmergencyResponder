<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubmissionStoreRequest;
use App\Models\Location;
use App\Models\Submission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function create(): View
    {
        $this->authorize('store', Submission::class);
        $enableLivewire = true;
        $withToast = true;

        return view('user.submissions-create', compact('enableLivewire', 'withToast'));
    }

    public function store(SubmissionStoreRequest $request): RedirectResponse
    {
        $this->authorize('store', Submission::class);

        DB::transaction(function () use ($request) {
            $submissionFillables = app(Submission::class)->getFillable();
            $locationFillables = app(Location::class)->getFillable();
            $submissionsData = array_merge($request->only($submissionFillables), ['submitted_by' => auth()->id()]);

            $submission = Submission::query()->create($submissionsData);
            $submission->location()->create($request->only($locationFillables));
            $submission->contacts()->createMany($request->get('contacts'));
            $submission->relatedLinks()->createMany($request->get('links'));

            \toastr()->success('Submission added successfully');
        }, 3);

        return redirect('/');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
