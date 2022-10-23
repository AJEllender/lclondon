@extends('layouts.app')

{{--
  -- @param \App\Models\EventType $event_type
  --}}

@section('content')
    <h1 class="sr-only">{{ $event_type->getName() }}</h1>

    {{-- Breadcrumbs --}}

    @if ($event_type->hasFlexibleContent('header'))
        @flexibleField($event_type, 'header', 'header')
    @endif

    @if ($event_type->hasFlexibleContent('content'))
        @flexibleField($event_type, 'content', 'content')
    @endif

    @if ($upcoming_events->count())
        <div data-label="" id="" class="relative w-full flex items-center justify-center my-12 sm:my-16 md:my-20 lg:my-24 px-6 sm:px-12">
            <div class="w-full max-w-xs sm:max-w-2xl lg:max-w-5xl">
                @component('enso-crud::flex-partials.components.title', [
                    'class' => 'mb-4 md:mb-6 lg:mb-10 text-center'
                ])
                    Upcoming Events
                @endcomponent

                @include('events.parts.event-list', [
                    'events' => $upcoming_events,
                ])
            </div>
        </div>
    @endif
@endsection
