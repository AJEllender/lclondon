@if ($buttons->isNotEmpty())
  <div class="{{ $wrapper_class ?? '' }}">
    @foreach ($buttons as $button)
      @include('enso-crud::flex-partials.components.button', [
        'url' => $button->link,
        'rel' => $button->rel,
        'target' => $button->target,
        'hover' => $button->hover,
        'label' => $button->label,
        'type' => $button->type ?? 'primary',
      ])
    @endforeach
  </div>
@endif
