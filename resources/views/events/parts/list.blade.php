<div class="flex flex-wrap -m-4 justify-center">
    @foreach ($events as $event)
        @include('events.parts.preview')
    @endforeach
</div>
