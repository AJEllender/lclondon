@php
    switch ($type ?? 'primary') {
        case 'primary':
            $classes = ['button', 'bg-blue-500', 'hover:bg-blue-700', 'text-white'];
            break;
        case 'secondary':
            $classes = ['button', 'bg-orange-500', 'hover:bg-orange-700', 'text-white'];
            break;
        default:
            $classes = ['button', 'bg-gray-500', 'hover:bg-gray-700', 'text-white'];
            break;
    }

    $class = implode(' ', $classes);
@endphp

<a
    href="{{ $url ?? '#' }}"
    @if (!empty($rel))
        rel="{{ $rel }}"
    @endif
    target="{{ $target ?? '_self' }}"
    class="font-bold {{ $class }}"
    title="{{ $hover ?? '' }}"
    >
        {{ $label ?? 'Click me' }}
</a>
