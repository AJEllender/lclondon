@php
    if ($image) {
        $type = 'image';
    } else {
        $type = 'empty';
    }

    $show_caption = $show_caption ?? true;
@endphp

@if($type === 'image')
    <figure>
        <picture class="bg-gray-300">
            <source
                srcset="{{ $image->getResizeUrl('768_x', true) }} 768w,
                    {{ $image->getResizeUrl('1024_x', true) }} 1024w,
                    {{ $image->getResizeUrl('1920_x', true) }}  1920w,
                    {{ $image->getResizeUrl('3840_x', true) }} 3840w"
                sizes="100vw"
                media="(min-width: 1024px)"
            >
            <img
                src="{{ $image->getResizeUrl('1024_x', true) }}"
                alt="{{ $image->alt_text }}"
                class="w-full"
                height="{{ Arr::get($image->fileinfo, 'height') }}"
                width="{{ Arr::get($image->fileinfo, 'width') }}"
            >
        </picture>
        @if($show_caption && $image->caption)
            <figcaption class="text-center text-sm text-gray-600 my-1" >{{ $image->caption }}</figcaption>
        @endif
    </figure>
@endif
