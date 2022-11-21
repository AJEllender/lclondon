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
@endsection
