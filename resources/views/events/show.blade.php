@extends('layouts.app')

{{--
  -- @param \App\Models\Event $event
  --}}

@section('content')
    <h1 class="sr-only">{{ $event->getEventName() }}</h1>

    {{-- Breadcrumbs --}}

    @if ($event->hasFlexibleContent('header'))
        @flexibleField($event, 'header', 'header')
    @elseif ($event->eventType && $event->eventType->hasFlexibleContent('header'))
        @flexibleField($event->eventType, 'header', 'header')
    @endif

    @if (!($event->isPublished()))
        @include('parts.unpublished-banner')
    @endif

    <div class="px-8 py-4 flex items-center justify-center bg-blue-500">
        <div class="w-full md:max-w-3xl flex justify-between items-center text-white flex-col sm:flex-row">
            <div class="font-bold text-2xl sm:text-3xl">{{ $event->getEventName() }}</div>
            <div class="font-bold text-xl sm:text-2xl">
                @if ($event->start_at)
                    @if ($event->end_at)
                        <div>{{ $event->start_at->format('jS M')  }}: {{ $event->start_at->format('H:i') }} - {{ $event->end_at->format('H:i') }}</div>
                    @else
                        <div>{{ $event->start_at->format('jS M')  }}: {{ $event->start_at->format('H:i') }} - Late</div>
                    @endif
                @else
                    <div class="px-2 my-2">TBC</div>
                @endif
            </div>
        </div>
    </div>

    {{-- Add Event Specific information, like date & time --}}

    @if ($event->hasFlexibleContent('content'))
        @flexibleField($event, 'content', 'content')
    @elseif ($event->eventType && $event->eventType->hasFlexibleContent('content'))
        @flexibleField($event->eventType, 'content', 'content')
    @endif

    @if ($upcoming_events->count())
        <div data-label="" id="" class="relative w-full flex items-center justify-center my-12 sm:my-16 md:my-20 lg:my-24 px-6 sm:px-12">
            <div class="w-full max-w-xs sm:max-w-2xl lg:max-w-5xl">
                @component('enso-crud::flex-partials.components.title', [
                    'class' => 'mb-4 md:mb-6 lg:mb-10 text-center'
                ])
                    Other Future {{ $event->eventType ? $event->eventType->getName() : '' }} Dates
                @endcomponent

                @include('events.parts.list', [
                    'events' => $upcoming_events,
                ])
            </div>
        </div>
    @endif

@endsection
