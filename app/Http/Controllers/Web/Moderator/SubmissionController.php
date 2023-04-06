<?php

namespace App\Http\Controllers\Web\Moderator;

use App\Enums\SubmissionStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Location;
use App\Models\RelatedLink;
use App\Models\Responder;
use App\Models\Submission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SubmissionController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Submission::class);

        $submissions = Submission::query()
            ->when(request('s'), function (Builder $q) {
                $q->search(request('s'));
            })
            ->when(request('f') && SubmissionStatusEnum::tryFrom((int) request('f'))?->titleCase(), function (Builder $q) {
                $q->where('status', request('f'));
            })
            ->when(request('f') && in_array(request('f'), ['no-mod', 'has-mod']), function (Builder $q) {
                $mod = match(request('f')) {
                    'no-mod' => "=",
                    'has-mod' => "!="
                };

                $q->where('monitored_by', $mod, null);
            })
            ->when(request('f') && in_array(request('f'), ['nearest', 'farthest']), function ($q) {
                if(is_numeric(request('_latitude')) && is_numeric(request('_longitude'))) {
                    if(request('f') === 'nearest') {
                        $q->nearest(request('_latitude'), request('_longitude'));
                    } else {
                        $q->farthest(request('_latitude'), request('_longitude'));
                    }
                }
            })
            ->with(['location', 'monitoredBy', 'emergencyType', 'contacts', 'submittedBy'])
            ->latest('updated_at')
            ->paginate(validatePerPage());
        $submissionsCount = cache()->remember('submissions-count', now()->addMinutes(30), fn () => Submission::query()->count());
        $statuses = SubmissionStatusEnum::cases();

        return view('moderator.submissions', compact('submissions', 'submissionsCount', 'statuses'));
    }



    public function show(Submission $submission): View
    {
        $this->authorize('view', $submission);

        $submission->load(['location', 'monitoredBy', 'emergencyType', 'contacts', 'submittedBy', 'relatedLinks']);

        return view('moderator.submissions-show', compact('submission'));
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
