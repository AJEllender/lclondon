@php
  /**
   * $row_data consists of:
   *   ->row_id - string
   *   ->row_label - string
   *   ->row_type - string
   *   ->eventhighlights - Collection of,
   *     ->event - Event,
   *     ->alignment - string (left|right)
   *   ->title - string
   */
  $row_data = $row->unpack();
@endphp

@if($row_data->eventhighlights->count())
    <div
        data-label="{{ $row_data->row_label }}"
        id="{{  $row_data->row_id }}"
        class="relative w-full flex items-center justify-center my-12 sm:my-16 md:my-20 lg:my-24 px-6 sm:px-12"
    >
        <div class="w-full md:max-w-5xl">
            @if (!empty($row_data->title))
                @component('enso-crud::flex-partials.components.title', [
                    'class' => 'mb-4 md:mb-6 lg:mb-10 text-center'
                ])
                    {{ $row_data->title }}
                @endcomponent
            @endif

            @foreach ($row_data->eventhighlights as $event_highlight)
                @include('events.parts.event-highlight', [
                    'event' => $event_highlight->event,
                    'alignment' => $event_highlight->alignment,
                    'class' => 'mb-4 md:mb-6'
                ])
            @endforeach
        </div>
    </div>
@endif
