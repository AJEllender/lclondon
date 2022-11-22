@php
  /**
   * $row_data consists of:
   *   ->row_id - string
   *   ->row_label - string
   *   ->row_type - string
   *   ->buttons, Collection - items which consist of:
   *     ->label - string
   *     ->hover - string - if not set, use the label as the hover tooltip
   *     ->link - string
   *     ->target - string
   *     ->rel - string
   *   ->event_types - Collection
   *   ->title - string
   */
  $row_data = $row->unpack();

  $top_margin = $row->previousRowHas('diminished-margins')
    ? 'mt-6 sm:mt8 md:mt-10 lg:mt-12'
    : 'mt-12 sm:mt-16 md:mt-20 lg:mt-24';

  $bottom_margin = $row->nextRowHas('diminished-margins')
    ? 'mb-6 sm:mb8 md:mb-10 lg:mb-12'
    : 'mb-12 sm:mb-16 md:mb-20 lg:mb-24';
@endphp

@if($row_data->events->count())
    <div class="w-full {{ $top_margin }} {{ $bottom_margin }} px-6 sm:px-12">
        <div class="w-full max-w-xs sm:max-w-2xl lg:max-w-5xl mx-auto">
            @component('enso-crud::flex-partials.components.title', [
                'class' => 'mb-4 md:mb-6 lg:mb-10 text-center'
            ])
                {{ !empty($row_data->title) ? $row_data->title : implode(' ', array_filter(['Future', ($row_data->event_type ? $row_data->event_type->name : null), 'dates'])) }}
            @endcomponent

            @include('events.parts.list', [
                'events' => $row_data->events,
            ])

            @component('enso-crud::flex-partials.components.buttons', [
                'wrapper_class' => 'mt-4 sm:mt-6 md:mt-8 text-center',
                'buttons' => $row_data->buttons,
            ])
            @endcomponent
        </div>
    </div>
@endif
