@php
  /**
   * $row_data consists of:
   *   ->row_label - string - Not relevant for current use-case
   *   ->row_id - string - for anchor tags in case they want to link with a # value
   *   ->images - Collection of ImageFile
   *   ->title - string
   */
  $row_data = $row->unpack();
@endphp

<div
    data-label="{{ $row_data->row_label }}"
    id="{{  $row_data->row_id }}"
    class="relative w-full flex items-center justify-center my-12 sm:my-16 md:my-20 lg:my-24 px-6 sm:px-12"
>
    <div class="w-full max-w-xs sm:max-w-2xl lg:max-w-5xl">
        @if (!empty($row_data->title))
            @component('enso-crud::flex-partials.components.title', [
                'class' => 'mb-4 md:mb-6 lg:mb-10 text-center'
            ])
                {{ $row_data->title }}
            @endcomponent
        @endif

        <gallery :images="{{ \App\Http\Resources\LightBoxImageResource::collection($row_data->images)->toJson() }}"></gallery>
    </div>
</div>
