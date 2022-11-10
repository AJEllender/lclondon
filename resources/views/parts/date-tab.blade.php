@php
    $class = $class ?? '';
    list($y, $x) = explode('-', $position ?? 'top-left');

    $y = ($y === 'top' ? '-top-1 rounded-b-lg rounded-t-md' : '-bottom-1 rounded-t-lg rounded-b-md');
    $x = ($x === 'right' ? 'right-4' : 'left-4');
@endphp

<div class="font-title absolute p-4 bg-gradient-to-br from-blue-500 to-blue-700 text-white {{ $x }} {{ $y }} {{ $class }}">
    <div class="w-full text-center font-bold text-lg">{{ $date->format('jS')  }}</div>
    <div class="w-full text-center font-bold text-xl">{{ $date->format('M')  }}</div>
</div>
