@extends('layouts.app')

{{--
  -- @param \App\Models\Page $page
  --}}

@section('content')
    <h1 class="sr-only">{{ $page->name }}</h1>

    @flexibleField($page, 'header', 'header')

    <event-calendar
        class="h-1/2 max-w-5xl mx-auto my-8 sm:my-12 lg:my-20 bg-white"
        base-url="{{ route('api.events.index') }}"
    ></event-calendar>

    {{-- Events listing --}}

    {{-- @flexibleField($page, 'content', 'content') --}}
@endsection
