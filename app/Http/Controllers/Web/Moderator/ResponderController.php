<?php

namespace App\Http\Controllers\Web\Moderator;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResponderUpdateRequest;
use App\Models\Location;
use App\Models\Responder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ResponderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $this->authorize('viewAny', Responder::class);

        $responders = Responder::query()
            ->when(request('s'), function (Builder $q) {
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
            ->latest('updated_at')
            ->paginate(validatePerPage());

        $respondersCount = cache()->remember('responders-count', now()->addMinutes(30), fn () => Responder::query()->count());

        return view('moderator.responders', compact('responders', 'respondersCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('store', Responder::class);
    }

    /**
     * Display the specified resource.
     */
    public function show(Responder $responder): View
    {
        $this->authorize('view', Responder::class);

        $responder->load(['location', 'emergencyType', 'contacts']);

        return view('moderator.responders-show', compact('responder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Responder $responder): View
    {
        $this->authorize('update', $responder);

        $enableLivewire = true;

        return view('moderator.responders-edit', compact('responder', 'enableLivewire'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ResponderUpdateRequest $request, Responder $responder): RedirectResponse
    {
        $this->authorize('update', $responder);

        $locationFillable = app(Location::class)->getFillable();

        DB::transaction(function () use (
            $responder,
            $request,
            $locationFillable,
        ) {
            $responder->update($request->only($responder->getFillable()));
            $responder->location()->update($request->only($locationFillable));
            $responder->contacts()->delete();
            $responder->relatedLinks()->delete();
            $responder->contacts()->createMany($request->input('contacts'));
            $responder->relatedLinks()->createMany($request->input('links'));
        });

        \toastr()->success('Responder updated sucessfully');

        return redirect()->route('moderator.responders.show', $responder->id);
    }
}
