@if ($buttons->isNotEmpty())
  <div class="{{ $wrapper_class ?? '' }}">
    @foreach ($buttons as $button)
      <a
        href="{{ $button->link }}"
        @if ($button->rel)
          rel="{{ $button->rel }}"
        @endif
        target="{{ $button->target }}"
        class="{{ $class ?? 'button' }}"
        title="{{ $button->hover }}"
      >
          {{ $button->label }}
      </a>
    @endforeach
  </div>
@endif
