<div class="grow-0 shrink-0 basis-full sm:basis-1/2 lg:basis-1/3 flex flex-col p-4">
    <a href="{{ $event->getUrl() }}" class="group grow w-full h-auto shadow-lg flex flex-col bg-white relative {{ !$event->isPublished ? 'unpublished' : '' }}">
        @if ($image = $event->getEventImage())
            <img class="w-full h-64 object-cover object-center grow-0 shrink-0"
                src="{{ $event->getEventImage()->preview }}"
                alt="{{ $event->getEventImage()->alt }}">
        @else
            <div class="w-full h-64">
        @endif

        <div class="grow shrink-0 font-title group-hover:bg-orange-100 p-2 font-bold text-xl bg-blue-100 ">{{ $event->getEventName() }}</div>

        @if ($event->start_at)
            @include('parts.date-tab', [
                'date' => $event->start_at,
                'position' => 'top-right',
                'class' => 'grow-0 shrink-0 group-hover:from-orange-500 group-hover:to-orange-700'
            ])

            @if ($event->end_at)
                <div class="grow-0 shrink-0 group-hover:bg-orange-200 p-2 font-bold text-lg bg-blue-200">{{ $event->start_at->format('H:i') }} - {{ $event->end_at->format('H:i') }}</div>
            @else
                <div class="grow-0 shrink-0 group-hover:bg-orange-200 p-2 font-bold text-lg bg-blue-200">{{ $event->start_at->format('H:i') }} - Late</div>
            @endif
        @else
            <div class="grow-0 shrink-0 group-hover:bg-orange-200 px-2 my-2">TBC</div>
        @endif
    </a>
</div>
