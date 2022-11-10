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
@endphp

<div
    data-label="{{ $row_data->row_label }}"
    id="{{  $row_data->row_id }}"
    class="w-full my-12 sm:my-16 md:my-20 lg:my-24 px-6 sm:px-12"
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


