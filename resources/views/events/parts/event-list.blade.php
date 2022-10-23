<div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-8">
    @foreach ($events as $event)
        @include('events.parts.event-preview')
    @endforeach
</div>
