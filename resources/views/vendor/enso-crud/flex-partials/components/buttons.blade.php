@if ($buttons->isNotEmpty())
  <div class="{{ $wrapper_class ?? '' }}">
    @foreach ($buttons as $button)
      @component('enso-crud::flex-partials.components.button', [
        'url' => $button->link,
        'rel' => $button->rel,
        'target' => $button->target,
        'hover' => $button->hover,
        'type' => $button->type ?? 'primary',
      ])
        {{ $button->label }}
      @endcomponent
    @endforeach
  </div>
@endif
