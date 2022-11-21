@extends('layouts.app')

{{--
  -- @param \App\Models\Event $event
  --}}

@section('content')
    <h1 class="sr-only">{{ $event->getEventName() }}</h1>

    @if ($event->hasFlexibleContent('header'))
        @flexibleField($event, 'header', 'header')
    @elseif ($event->eventType && $event->eventType->hasFlexibleContent('header'))
        {{--
            Due to new functionality that lets the FlexibleRow unpacking process
            know the model of the parent object, we need to replace the content
            here so that the 'parent' model is actually correct when not using
            override content.
        --}}
        @php $event->header = $event->eventType->header @endphp
        @flexibleField($event, 'header', 'header')
    @endif

    @if (!($event->isPublished()))
        @include('parts.unpublished-banner')
    @endif

    <div class="px-8 py-4 flex items-center justify-center bg-blue-500">
        <div class="w-full md:max-w-3xl flex justify-between items-center text-white flex-col sm:flex-row">
            <div class="font-title font-bold text-2xl sm:text-3xl">{{ $event->getEventName() }}</div>
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

    @if ($event->hasFlexibleContent('content'))
        @flexibleField($event, 'content', 'content')
    @elseif ($event->eventType && $event->eventType->hasFlexibleContent('content'))
        {{--
            Due to new functionality that lets the FlexibleRow unpacking process
            know the model of the parent object, we need to replace the content
            here so that the 'parent' model is actually correct when not using
            override content.
        --}}
        @php $event->content = $event->eventType->content @endphp
        @flexibleField($event, 'content', 'content')
    @endif
@endsection
