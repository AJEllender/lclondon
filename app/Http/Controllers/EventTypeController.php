<?php

namespace App\Http\Controllers;

use App\Models\EventType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Yadda\Enso\Crud\Traits\UsesPage;
use Yadda\Enso\Facades\EnsoCrud;

class EventTypeController extends Controller
{
    use UsesPage;

    /**
     * Show an EventType
     *
     * @param EventType $event_type
     *
     * @return \Illuminate\View\View
     */
    public function show(EventType $event_type): \Illuminate\View\View
    {
        if (Gate::denies('view', $event_type)) {
            abort(404);
        }

        $upcoming_events = $event_type->events()
            ->upcoming()
            ->orderBy('start_at', 'asc')
            ->limit(3)
            ->get();

        return View::make('event-types.show', compact('event_type', 'upcoming_events'));
    }
}
