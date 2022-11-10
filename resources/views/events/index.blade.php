@extends('layouts.app')

{{--
  -- @param \App\Models\Page $page
  --}}

@section('content')
    <h1 class="sr-only">{{ $page->name }}</h1>

    @flexibleField($page, 'header', 'header')

    <div class="relative w-full flex items-center justify-center my-12 sm:my-16 md:my-20 lg:my-24 px-6 sm:px-12">
        <div class="w-full md:max-w-3xl">
            @foreach($event_types as $event_type)
                @include('event-types.parts.list-preview', [
                    'class' => $loop->index ? 'mt-4' : '',
                ])
            @endforeach
        </div>
    </div>

    <event-calendar
        class="max-w-5xl mx-auto my-8 sm:my-12 lg:my-20 bg-white vue-calendar"
        base-url="{{ route('api.events.index') }}"
    ></event-calendar>

    {{-- Events listing --}}

    {{-- @flexibleField($page, 'content', 'content') --}}
@endsection
