<?php

namespace App\Http\Controllers\Web\Public;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use Illuminate\View\View;

class SubmissionController extends Controller
{
    public function index(): View
    {
        $submissions = Submission::query()
            ->when(request('s'), function ($q) {
                $q->search(request('s'));
            })
            ->when(request('f') && in_array(request('f'), ['nearest', 'farthest']), function ($q) {
                if (is_numeric(request('_latitude')) && is_numeric(request('_longitude'))) {
                    if (request('f') === 'nearest') {
                        $q->nearest(request('_latitude'), request('_longitude'));
                    } else {
                        $q->farthest(request('_latitude'), request('_longitude'));
                    }
                }
            })
            ->with(['location', 'monitoredBy', 'emergencyType', 'contacts', 'submittedBy'])
            ->paginate(validatePerPage());

        return view('public.submissions', compact('submissions'));
    }

    public function show(Submission $submission): View
    {
        $submission->load(['location', 'monitoredBy', 'emergencyType', 'contacts', 'submittedBy', 'relatedLinks']);

        return view('public.submissions-show', compact('submission'));
    }
}
