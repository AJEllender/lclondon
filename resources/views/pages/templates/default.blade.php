@extends('layouts.app')

@section('content')
    <h1 class="sr-only">{{ $page->title }}</h1>

    @if ($page->hasFlexibleContent('header'))
        @flexibleField($page, 'header', 'header')
    @endif

    @if (!($page->isPublished()))
        @include('parts.unpublished-banner')
    @endif

    @if ($page->hasFlexibleContent('content'))
        @flexibleField($page, 'content', 'content')
    @endif
@endsection
