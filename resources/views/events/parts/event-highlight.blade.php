<a
    href="{{ route('events.show', [$event->eventType->slug, $event->uuid]) }}"
    class="flex flex-col {{ (($alignment ?? 'left') === 'right') ? 'md:flex-row-reverse' : 'md:flex-row' }} {{ $event->isPublished() ? '' : 'unpublished'}} {{ !empty($class) ? $class : ''}}"
>
    <div class="grow-0 shrink-0 h-72 w-72 self-center md:self-start">
        @if ($event->getEventImage())
            <img src="{{ $event->getEventImage()->getResizeUrl('preview_4x', true) }}" alt="{{ $event->getEventName() }}">
        @endif
    </div>
    <div class="bg-gray-100 px-8 py-4 grow shrink flex flex-col justify-center">
        <div class="text-3xl">
            {{ $event->getFullEventName() }}
        </div>
        @if ($event->start_at)
            <div class="text-3xl mt-4">
                {{ $event->start_at->format('jS M') }}
            </div>
        @endif
        @if ($event->getEventExcerpt())
            <div class="text-xl mt-4">
                {{ $event->getEventExcerpt() }}
            </div>
        @endif
    </div>
</a>
