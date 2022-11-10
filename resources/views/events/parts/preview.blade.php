<a href="{{ $event->getUrl() }}" class="event-preview w-full h-auto shadow-lg flex flex-col bg-white relative {{ !$event->isPublished ? 'unpublished' : '' }}">
    @if ($image = $event->getEventImage())
        <img class="w-full h-64 object-cover object-center"
            src="{{ $event->getEventImage()->preview }}"
            alt="{{ $event->getEventImage()->alt }}">
    @else
        <div class="w-full h-64">
    @endif

    <div class="event-preview__title p-2 font-bold text-xl bg-blue-100 grow">{{ $event->getEventName() }}</div>

    @if ($event->start_at)
        @include('parts.date-tab', [
            'date' => $event->start_at,
            'position' => 'top-right',
            'class' => 'event-preview__date-tab'
        ])

        @if ($event->end_at)
            <div class="event-preview__date p-2 font-bold text-lg bg-blue-200">{{ $event->start_at->format('H:i') }} - {{ $event->end_at->format('H:i') }}</div>
        @else
            <div class="event-preview__date p-2 font-bold text-lg bg-blue-200">{{ $event->start_at->format('H:i') }} - Late</div>
        @endif
    @else
        <div class="event-preview__date px-2 my-2">TBC</div>
    @endif
</a>
