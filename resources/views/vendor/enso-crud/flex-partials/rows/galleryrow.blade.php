@php
  /**
   * $row_data consists of:
   *   ->row_label - string - Not relevant for current use-case
   *   ->row_id - string - for anchor tags in case they want to link with a # value
   *   ->images - Collection of ImageFile
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

<div
    data-label="{{ $row_data->row_label }}"
    id="{{  $row_data->row_id }}"
    class="relative w-full flex items-center justify-center {{ $top_margin }} {{ $bottom_margin }} px-6 sm:px-12"
>
    <div class="w-full max-w-xs sm:max-w-2xl lg:max-w-5xl">
        @if (!empty($row_data->title))
            @component('enso-crud::flex-partials.components.title', [
                'class' => 'mb-4 md:mb-6 lg:mb-10 text-center'
            ])
                {{ $row_data->title }}
            @endcomponent
        @endif

        <gallery
            :images="{{ \App\Http\Resources\LightBoxImageResource::collection($row_data->images)->toJson() }}"
            :per-row="{{ $row_data->per_row }}"
        ></gallery>
    </div>
</div>
