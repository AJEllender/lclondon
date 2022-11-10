<a
    href="{{ route('events.show', [$event->eventType->slug, $event->uuid]) }}"
    class="group md:hover:shadow-lg flex flex-col {{ (($alignment ?? 'left') === 'right') ? 'md:flex-row-reverse' : 'md:flex-row' }} {{ $event->isPublished() ? '' : 'unpublished'}} {{ !empty($class) ? $class : ''}}"
>
    <div class="relative grow-0 shrink-0 h-48 w-48 sm:h-72 sm:w-72 self-center md:self-start">
        @if ($event->getEventImage())
            <img src="{{ $event->getEventImage()->getResizeUrl('preview_4x', true) }}" alt="{{ $event->getEventName() }}">
        @endif

        @if ($event->start_at)
            @include('parts.date-tab', [
                'date' => $event->start_at,
                'position' => (($alignment ?? 'left') === 'right') ? 'top-right' : 'top-left',
                'class' => 'group-hover:from-orange-500 group-hover:to-orange-700'
            ])
        @endif
    </div>
    <div class="group-hover:bg-orange-100 bg-white px-4 sm:px-8 py-4 sm:py-8 grow shrink flex flex-col justify-center">
        <div class="text-xl sm:text-3xl">
            {{ $event->getFullEventName() }}
        </div>
        @if ($event->getEventExcerpt())
            <div class="text-lg sm:text-xl mt-4">
                {{ $event->getEventExcerpt() }}
            </div>
        @endif
    </div>
</a>
