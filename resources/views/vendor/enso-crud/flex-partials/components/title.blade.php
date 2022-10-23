@if ($slot)
  <h2 class="text-2xl md:text-4xl lg:text-5xl font-bold leading-none {{ !empty($class) ? $class : '' }}">
    {{ $slot }}
  </h2>
@endif
