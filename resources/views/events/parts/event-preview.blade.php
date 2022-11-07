<a href="{{ $event->getUrl() }}" class="w-full h-auto shadow-lg flex flex-col bg-white relative {{ !$event->isPublished ? 'unpublished' : '' }}">
    @if ($image = $event->getEventImage())
        <img class="w-full h-64 object-cover object-center"
            src="{{ $event->getEventImage()->preview }}"
            alt="{{ $event->getEventImage()->alt }}">
    @else
        <div class="w-full h-64">
    @endif

    <div class="p-2 font-bold text-xl bg-blue-100 grow">{{ $event->getEventName() }}</div>

    @if ($event->start_at)
        <div class="p-4 absolute top-0 right-4 bg-blue-500 text-white rounded-b-lg">
            <div class="w-full text-center font-bold text-lg">{{ $event->start_at->format('jS')  }}</div>
            <div class="w-full text-center font-bold text-xl">{{ $event->start_at->format('M')  }}</div>
        </div>
        @if ($event->end_at)
            <div class="p-2 font-bold text-lg bg-blue-200">{{ $event->start_at->format('H:i') }} - {{ $event->end_at->format('H:i') }}</div>
        @else
            <div class="p-2 font-bold text-lg bg-blue-200">{{ $event->start_at->format('H:i') }} - Late</div>
        @endif
    @else
        <div class="px-2 my-2">TBC</div>
    @endif
</a>
