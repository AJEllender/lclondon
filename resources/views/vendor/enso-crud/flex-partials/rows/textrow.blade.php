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
   *   ->content - string - HTML
   *   ->title - string
   */
  $row_data = $row->unpack();
@endphp

<div
    data-label="{{ $row_data->row_label }}"
    id="{{  $row_data->row_id }}"
    class="relative w-full flex items-center justify-center my-12 sm:my-16 md:my-20 lg:my-24 px-6 sm:px-12"
>
    <div class="w-full md:max-w-3xl">
        @if (!empty($row_data->title))
            @component('enso-crud::flex-partials.components.title', [
                'class' => 'mb-4 md:mb-6 lg:mb-10'
            ])
                {{ $row_data->title }}
            @endcomponent
        @endif

        @component('enso-crud::flex-partials.components.content', [
            'class' => 'prose prose-lg'
        ])
            {!! $row_data->content !!}
        @endcomponent

        @component('enso-crud::flex-partials.components.buttons', [
            'wrapper_class' => 'mt-4 sm:mt-6 md:mt-8 text-center',
            'buttons' => $row_data->buttons,
        ])
        @endcomponent
    </div>
</div>

