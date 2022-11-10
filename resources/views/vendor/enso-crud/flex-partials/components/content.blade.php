@php
  if (empty($class)) {
    $class = '';
  }
@endphp

@if ($slot)
  <div class="font-sans text-base md:text-lg lg:text-xl {{ $class }}">
    {!! $slot !!}
  </div>
@endif
