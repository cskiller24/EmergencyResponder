<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubmissionStoreRequest;
use App\Http\Requests\SubmissionUpdateRequest;
use App\Models\Location;
use App\Models\Submission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SubmissionController extends Controller
{

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

        return redirect()->route('public.index');
    }

    public function edit(Submission $submission): View
    {
        $submission->load(['location', 'monitoredBy', 'emergencyType', 'contacts', 'submittedBy', 'relatedLinks']);

        return view('user.submission-edit', compact('submission'));
    }

    public function update(SubmissionUpdateRequest $request, Submission $submission): RedirectResponse
    {
        $this->authorize('update', $submission);

        $locationFillable = app(Location::class)->getFillable();
        DB::transaction(function () use (
            $submission,
            $request,
            $locationFillable,
        ) {
            $submission->update($request->only($submission->getFillable()));
            $submission->location()->update($request->only($locationFillable));
            $submission->contacts()->delete();
            $submission->relatedLinks()->delete();
            $submission->contacts()->createMany($request->input('contacts'));
            $submission->relatedLinks()->createMany($request->input('links'));

            \toastr()->success('Submission updated successfully');
        });

        return redirect()->route('public.submissions.show', $submission->id);
    }
}
