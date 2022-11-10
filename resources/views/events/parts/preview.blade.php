<a href="{{ $event->getUrl() }}" class="group w-full h-auto shadow-lg flex flex-col bg-white relative {{ !$event->isPublished ? 'unpublished' : '' }}">
    @if ($image = $event->getEventImage())
        <img class="w-full h-64 object-cover object-center"
            src="{{ $event->getEventImage()->preview }}"
            alt="{{ $event->getEventImage()->alt }}">
    @else
        <div class="w-full h-64">
    @endif

    <div class="group-hover:bg-orange-100 p-2 font-bold text-xl bg-blue-100 grow">{{ $event->getEventName() }}</div>

    @if ($event->start_at)
        @include('parts.date-tab', [
            'date' => $event->start_at,
            'position' => 'top-right',
            'class' => 'group-hover:from-orange-500 group-hover:to-orange-700'
        ])

        @if ($event->end_at)
            <div class="group-hover:bg-orange-200 p-2 font-bold text-lg bg-blue-200">{{ $event->start_at->format('H:i') }} - {{ $event->end_at->format('H:i') }}</div>
        @else
            <div class="group-hover:bg-orange-200 p-2 font-bold text-lg bg-blue-200">{{ $event->start_at->format('H:i') }} - Late</div>
        @endif
    @else
        <div class="group-hover:bg-orange-200 px-2 my-2">TBC</div>
    @endif
</a>
