@php
    $row_data = $row->unpack();

    $bg_color_class = \App\Utilities\BackgroundColor::from($row_data->background_color);

    switch ($row_data->font_color) {
        case 'dark':
            $text_color_class = 'text-gray-900';
            break;
        case 'light':
        default:
            $text_color_class = 'text-white';
    }

    switch ($row_data->background_texture) {
        case 'leather':
            $background_url = asset('img/large-leather.png');
            break;
        case 'asphalt':
            in_array($row_data->background_color, ['black', 'dark'])
                ? $background_url = asset('img/asfalt-light.png')
                : $background_url = asset('img/asfalt-dark.png');
            break;
        case "none":
        default:
            $background_url = '';
    }

    $image_padding = $row_data->image
        ? 'py-8 sm:py-16 md:py-24 lg:py-32 xl:py-48'
        : 'py-8 sm:py-12 md:py-16 lg:py-20 xl:py-24';
@endphp

<div
    data-label="{{ $row_data->row_label }}"
    id="{{  $row_data->row_id }}"
    class="relative w-full flex items-center justify-center {{ $image_padding }} {{ $bg_color_class }} {{ $text_color_class }}"
    style="background-image: url('{{ $background_url }}')"
>
    @if($row_data->image)
        <picture>
            <source
                srcset="{{ $row_data->image->getResizeUrl('hero_mobile', true) }} 1x,
                {{ $row_data->image->getResizeUrl('hero_mobile_2x', true) }} 2x"
                sizes="100vw"
                media="(max-width: 1024px)">
            <source
                srcset="{{ $row_data->image->getResizeUrl('hero', true) }} 1024w,
                    {{ $row_data->image->getResizeUrl('hero_lg', true) }}  1920w,
                    {{ $row_data->image->getResizeUrl('hero_lg_2x', true) }} 3840w"
                sizes="100vw"
                media="(min-width: 1024px)">
            <img
                src="{{ $row_data->image->getResizeUrl('hero', true) }}"
                alt="{{ $row_data->image->alt_text }}"
                class="full-bg-cover">
        </picture>
    @endif

    <div class="relative w-full flex items-center justify-center px-6 sm:px-12">
        <div class="text-center md:max-w-3xl">
            @if ($row_data->title)
                <h2 class="text-4xl font-bold mb-3">
                    {{ $row_data->title }}
                </h2>
            @endif

            @if ($row_data->content)
            {!!  $row_data->content !!}
            @endif

            @include('enso-crud::flex-partials.blocks.buttons', [
                'button_group' => 'mt-8',
            ])
        </div>
    </div>
</div>
