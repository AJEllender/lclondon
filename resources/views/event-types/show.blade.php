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

    @if (!($event_type->isPublished()))
        @include('parts.unpublished-banner')
    @endif

    @if ($event_type->hasFlexibleContent('content'))
        @flexibleField($event_type, 'content', 'content')
    @endif

    @if ($upcoming_events->count())
        <div class="w-full my-12 sm:my-16 md:my-20 lg:my-24 px-6 sm:px-12">
            <div class="w-full max-w-xs sm:max-w-2xl lg:max-w-5xl mx-auto">
                @component('enso-crud::flex-partials.components.title', [
                    'class' => 'mb-4 md:mb-6 lg:mb-10 text-center'
                ])
                    Future {{ $event_type->getName() }} Dates
                @endcomponent

                @include('events.parts.list', [
                    'events' => $upcoming_events,
                ])
            </div>

            @if ($upcoming_events->count() > 3)
                <event-calendar
                    class="max-w-5xl mx-auto bg-white vue-calendar my-12 sm:my-16 md:my-20 lg:my-24"
                    base-url="{{ route('api.events.index', ['event_type' => $event_type->slug]) }}"
                ></event-calendar>
            @endif
        </div>
    @endif
@endsection
