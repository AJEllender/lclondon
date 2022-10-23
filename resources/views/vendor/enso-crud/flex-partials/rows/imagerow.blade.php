@php
    /**
     * $row_data consists of:
     *   ->row_id - string
     *   ->row_label - string
     *   ->row_type - string
     *   ->image - ImageFile|null
     *   ->style - string (narrow, wide, full-width)
     *   ->title - string
     */
    $row_data = \App\Crud\Rows\ImageRow::unpack($row);

    switch ($row_data->style) {
        case 'narrow':
            $image_class = 'w-full md:max-w-3xl';
            break;
        case 'full-width':
            $image_class = 'w-full';
            break;
        case 'wide':
        default:
            $image_class = 'w-full md:max-w-5xl';
    }

@endphp

<div
    data-label="{{ $row_data->row_label }}"
    id="{{  $row_data->row_id }}"
    class="relative w-full flex items-center justify-center"
>
    <div class="{{ $image_class }}">
        @if (!empty($row_data->title))
            @component('enso-crud::flex-partials.components.title', [
                'class' => 'mb-4 md:mb-6 lg:mb-10 text-center'
            ])
                {{ $row_data->title }}
            @endcomponent
        @endif
        @component('enso-crud::flex-partials.components.media', [
            'image' => $row_data->image,
            'show_caption' => $row_data->style !== 'full-width',
        ])
        @endcomponent
    </div>
</div>
