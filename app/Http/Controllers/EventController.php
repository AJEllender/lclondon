<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Yadda\Enso\Crud\Traits\UsesPage;
use Yadda\Enso\Facades\EnsoCrud;

class EventController extends Controller
{
    use UsesPage;

    /**
     * Show an Event
     *
     * @param Event $event
     *
     * @return \Illuminate\View\View
     */
    public function show(EventType $event_type, Event $event): \Illuminate\View\View
    {
        if (Gate::denies('view', $event_type) || Gate::denies('view', $event)) {
            abort(404);
        }

        $upcoming_events = $event_type->events()
            ->upcoming()
            ->where($event->getKeyName(), '!=', $event->getKey())
            ->orderBy('start_at', 'asc')
            ->limit(3)
            ->get();

        return View::make('events.show', compact(
            'event',
            'event_type',
            'upcoming_events'
        ));
    }
}
