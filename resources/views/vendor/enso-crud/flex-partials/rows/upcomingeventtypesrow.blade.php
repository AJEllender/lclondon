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

@if($row_data->event_types->count())
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

        <div class="relative w-full flex items-center justify-center">
            <div class="w-full md:max-w-3xl">
                @foreach($row_data->event_types as $event_type)
                    @include('event-types.parts.list-preview', [
                        'class' => $loop->index ? 'mt-4' : '',
                    ])
                @endforeach
            </div>
        </div>

        @component('enso-crud::flex-partials.components.buttons', [
            'wrapper_class' => 'mt-4 sm:mt-6 md:mt-8 text-center',
            'buttons' => $row_data->buttons,
        ])
        @endcomponent
    </div>
@endif


