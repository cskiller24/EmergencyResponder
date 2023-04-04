<?php

namespace App\Http\Controllers\Web\Moderator;

use App\Enums\SubmissionStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubmissionStoreRequest;
use App\Http\Requests\SubmissionUpdateRequest;
use App\Models\Contact;
use App\Models\Location;
use App\Models\RelatedLink;
use App\Models\Responder;
use App\Models\Submission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Request;

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
            ->when(request('f') && SubmissionStatusEnum::tryFrom((int) request('f'))?->titleCase(), function (Builder $q) {
                $q->where('status', request('filter'));
            })
            ->when(request('f') && in_array(request('f'), ['no-mod', 'has-mod']), function (Builder $q) {
                $mod = match(request('f')) {
                    'no-mod' => "=",
                    'has-mod' => "!="
                };

                $q->where('monitored_by', $mod, null);
            })
            ->with(['location', 'monitoredBy', 'emergencyType', 'contacts', 'submittedBy'])
            ->latest('updated_at')
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

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function approveSubmission(Submission $submission): RedirectResponse
    {
        $this->authorize('approveDeny', $submission);

        throw_unless(
            $submission->status === SubmissionStatusEnum::SUBMITTED,
            ValidationException::withMessages(['error' => 'You cannot approve a non-submitted submission'])
        );

        $submission->load(['location', 'monitoredBy', 'emergencyType', 'contacts', 'submittedBy', 'relatedLinks']);

        $responderFillable = app(Responder::class)->getFillable();
        $locationFillables = app(Location::class)->getFillable();
        $contactsFillables = app(Contact::class)->getFillable();
        $relatedLinksFillable = app(RelatedLink::class)->getFillable();

        $contactsData = $submission->contacts->map(
            fn (Contact $data) => $data->only($contactsFillables))
            ->toArray();
        $relatedLinksData = $submission->relatedLinks->map(
            fn (RelatedLink $data) => $data->only($relatedLinksFillable))
            ->toArray();

        DB::transaction(function () use (
            $submission, $responderFillable, $locationFillables, $contactsData, $relatedLinksData
            ) {
            $submission->update(['status' => SubmissionStatusEnum::APPROVED]);

            $responder = Responder::query()->create($submission->only($responderFillable));
            $responder->location()->create($submission->location->only($locationFillables));
            $responder->contacts()->createMany($contactsData);
            $responder->relatedLinks()->createMany($relatedLinksData);

            \toastr()->success('Sucessfully approve submission');
        }, 3);

        return redirect()->route('moderator.submissions.index');
    }

    public function denySubmission(Submission $submission): RedirectResponse
    {
        throw_unless(
            $submission->status === SubmissionStatusEnum::SUBMITTED,
            ValidationException::withMessages(['error' => 'You cannot deny a non-submitted submission'])
        );

        $this->authorize('approveDeny', $submission);

        $submission->update(['status' => SubmissionStatusEnum::DECLINED]);

        \toastr()->success('Successfully declined submission');
        return redirect()->route('moderator.submissions.show', $submission->id);
    }

    public function addModerator(Submission $submission): RedirectResponse
    {
        throw_unless(
            $submission->hasNoMaintainer(),
            ValidationException::withMessages(['error' => 'The submission already have maintainer'])
        );

        $this->authorize('addModerator', $submission);

        $submission->update(['monitored_by' => auth()->id()]);

        \toastr()->success('Successfully added moderator');
        return redirect()->route('moderator.submissions.show', $submission->id);

    }
}
