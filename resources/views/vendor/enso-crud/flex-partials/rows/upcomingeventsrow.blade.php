@php
  /**
   * $row_data consists of:
   *   ->row_id - string
   *   ->row_label - string
   *   ->row_type - string
   *   ->event_types - Collection
   *   ->title - string
   */
  $row_data = $row->unpack();
@endphp

@if($row_data->events->count())
    <div class="w-full my-12 sm:my-16 md:my-20 lg:my-24 px-6 sm:px-12">
        <div class="w-full max-w-xs sm:max-w-2xl lg:max-w-5xl mx-auto">
            @component('enso-crud::flex-partials.components.title', [
                'class' => 'mb-4 md:mb-6 lg:mb-10 text-center'
            ])
                {{ !empty($row_data->title) ? $row_data->title : implode(' ', array_filter(['Future', ($row_data->event_type ? $row_data->event_type->name : null), 'dates'])) }}
            @endcomponent

            @include('events.parts.list', [
                'events' => $row_data->events,
            ])
        </div>
    </div>
@endif