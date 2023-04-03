<?php

namespace App\Http\Controllers\Web\Moderator;

use App\Enums\SubmissionStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubmissionRequest;
use App\Http\Requests\SubmissionUpdateRequest;
use App\Models\Location;
use App\Models\Submission;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $this->authorize('viewAny', Submission::class);

        $submissions = Submission::query()
            ->when(request('s'), function (Builder $q) {
                $q->search(request('s'));
            })
            ->when(request('f') && SubmissionStatusEnum::tryFrom(request('f'))?->titleCase(), function (Builder $q) {
                $q->where('status', request('filter'));
            })
            ->with(['location', 'monitoredBy', 'emergencyType', 'contacts', 'submittedBy'])
            ->paginate(validatePerPage());
        $submissionsCount = cache()->remember('submissions-count', now()->addMinutes(30), fn () => Submission::query()->count());
        $statuses = SubmissionStatusEnum::cases();

        return view('moderator.submissions', compact('submissions', 'submissionsCount', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('store', Submission::class);
        $enableLivewire = true;
        $withToast = true;

        return view('user.submissions-create', compact('enableLivewire', 'withToast'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubmissionRequest $request): RedirectResponse
    {
        $this->authorize('store', Submission::class);

        DB::beginTransaction();
        try {
            $submissionFillables = app(Submission::class)->getFillable();
            $locationFillables = app(Location::class)->getFillable();
            $submissionsArray = array_merge($request->only($submissionFillables), ['submitted_by' => auth()->id()]);

            $submission = Submission::query()->create($submissionsArray);
            $submission->location()->create($request->only($locationFillables));
            $submission->contacts()->createMany($request->get('contacts'));
            $submission->relatedLinks()->createMany($request->get('links'));

            DB::commit();
            \toastr()->success('Submission added successfully');
        } catch (QueryException $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect('/');
    }

    /**
     * Display the specified resource.
     */
    public function show(Submission $submission): View
    {
        $this->authorize('view', $submission);

        $submission->load(['location', 'monitoredBy', 'emergencyType', 'contacts', 'submittedBy', 'relatedLinks']);

        return view('moderator.submissions-show', compact('submission'));
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
    public function update(SubmissionUpdateRequest $request, Submission $submission): RedirectResponse
    {
        if ($request->status === SubmissionStatusEnum::DRAFT) {
            $this->authorize('submission->update');

            $submission->update($request->validated());
        }

        if ($request->status === SubmissionStatusEnum::APPROVED) {
            $this->authorize('approveDenySubmission', Submission::class);

            return $this->approveSubmission($request, $submission);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function approveSubmission(SubmissionUpdateRequest $request, Submission $submission): RedirectResponse
    {
    }
}
