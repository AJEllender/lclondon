@php
    $next_event = $event_type->events->first();
    $is_published = $next_event
        ? $next_event->isPublished()
        : $event_type->isPublished();
@endphp

<a href="{{ route('event-types.show', $event_type->slug) }}" class="group md:hover:shadow-lg flex {{ $is_published ? '' : 'unpublished'}} {{ isset($class) ? $class : '' }}">
    <div class="grow-0 shrink-0 h-24 w-24">
        @if ($event_type->image)
            <img src="{{ $event_type->image->getResizeUrl('preview_1x', true) }}" alt="{{ $event_type->name }}">
        @endif
    </div>
    <div class="group-hover:bg-orange-100 bg-blue-100 p-4 grow shrink flex flex-col justify-center">
        <div class="font-title text-lg sm:text-xl md:text-2xl">
            @if ($next_event)
                {{ $next_event->getFullEventName() }}
            @else
                {{ $event_type->getName() }}
            @endif
        </div>
        @if ($next_event && $next_event->start_at)
            <div class="text-sm sm:text-base md:text-lg">Next Event: {{ $next_event->start_at->format('jS M') }}</div>
        @endif
    </div>
</a>
