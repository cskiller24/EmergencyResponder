<?php

namespace App\Http\Controllers\Web\Public;

use App\Http\Controllers\Controller;
use App\Models\Responder;
use Illuminate\View\View;

class ResponderController extends Controller
{
    public function index(): View
    {
        $responders = Responder::query()
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
            ->with(['location', 'emergencyType', 'contacts'])
            ->paginate(validatePerPage());

        return view('public.responders', compact('responders'));
    }

    public function show(Responder $responder): View
    {
        $responder->load(['location', 'emergencyType', 'contacts', 'relatedLinks']);

        return view('public.responders-show', compact('responder'));
    }
}
