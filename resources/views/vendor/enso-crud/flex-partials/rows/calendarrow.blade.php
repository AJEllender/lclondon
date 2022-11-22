@php
  /**
   * $row_data consists of:
   *   ->row_id - string
   *   ->row_label - string
   *   ->row_type - string
   *   ->event_type - EventType|null
   *   ->title - string
   */
  $row_data = $row->unpack();

  $calendar_url = $row_data->event_type
    ? route('api.events.index', ['event_type' => $row_data->event_type->slug])
    : route('api.events.index');

  $top_margin = $row->previousRowHas('diminished-margins')
    ? 'mt-6 sm:mt8 md:mt-10 lg:mt-12'
    : 'mt-12 sm:mt-16 md:mt-20 lg:mt-24';

  $bottom_margin = $row->nextRowHas('diminished-margins')
    ? 'mb-6 sm:mb8 md:mb-10 lg:mb-12'
    : 'mb-12 sm:mb-16 md:mb-20 lg:mb-24';
@endphp

<div
    data-label="{{ $row_data->row_label }}"
    id="{{  $row_data->row_id }}"
    class="w-full {{ $top_margin }} {{ $bottom_margin }} px-6 sm:px-12"
>
    @if (!empty($row_data->title))
        <div class="w-full md:max-w-3xl mx-auto">
            @component('enso-crud::flex-partials.components.title', [
                'class' => 'mb-4 md:mb-6 lg:mb-10 text-center',
            ])
                {{ $row_data->title }}
            @endcomponent
        </div>
    @endif

    <event-calendar
        class="max-w-5xl mx-auto bg-white vue-calendar"
        base-url="{{ $calendar_url }}"
    ></event-calendar>
</div>


