@php
  if (empty($class)) {
    $class = '';
  }
@endphp

@if ($slot)
  <div class="text-base md:text-lg lg:text-xl {{ $class }}">
    {!! $slot !!}
  </div>
@endif
