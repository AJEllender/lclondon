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
@endphp

@if($row_data->event_types->count())
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


