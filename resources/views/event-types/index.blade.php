@extends('layouts.app')

{{--
  -- @param \App\Models\Page $page
  --}}

@section('content')
    <h1 class="sr-only">{{ $page->name }}</h1>

    {{-- Breadcrumbs --}}

    @if ($page->hasFlexibleContent('header'))
        @flexibleField($page, 'header', 'header')
    @endif

    @if ($page->hasFlexibleContent('content'))
        @flexibleField($page, 'content', 'content')
    @endif

    {{-- List of event types --}}
@endsection
