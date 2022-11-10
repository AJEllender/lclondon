@php
  /**
   * $row_data consists of:
   *   ->row_id - string
   *   ->row_label - string
   *   ->row_type - string
   *   ->alignment - string - left|right
   *   ->buttons, Collection - items which consist of:
   *     ->label - string
   *     ->hover - string - if not set, use the label as the hover tooltip
   *     ->link - string
   *     ->target - string
   *     ->rel - string
   *   ->content - string - HTML
   *   ->image - ImageFile|null
   *   ->title - string
   */
  $row_data = $row->unpack();
@endphp

<div
    data-label="{{ $row_data->row_label }}"
    id="{{  $row_data->row_id }}"
    class="w-full my-12 sm:my-16 md:my-20 lg:my-24 px-6 sm:px-12"
>
    <div class="w-full md:max-w-5xl m-auto flex flex-col-reverse {{ $row_data->alignment === 'left' ? 'md:flex-row-reverse' : 'md:flex-row' }}">
        <div class="shrink-0 grow-0 basis-1/2 bg-white p-8 flex flex-col">
            @if (!empty($row_data->title))
                @component('enso-crud::flex-partials.components.title', [
                    'class' => 'mb-4 md:mb-6 lg:mb-10'
                ])
                    {{ $row_data->title }} {{ $row_data->alignment }}
                @endcomponent
            @endif

            @component('enso-crud::flex-partials.components.content', [
                'class' => 'prose prose-lg mb-4'
            ])
                {!! $row_data->content !!}
            @endcomponent

            @if (!empty($row_data->buttons->count()))
                <div class="md:mt-auto flex justify-end flex-wrap">
                    @foreach ($row_data->buttons as $button)
                        @component('enso-crud::flex-partials.components.button', [
                            'url' => $button->link,
                            'rel' => $button->rel,
                            'target' => $button->target,
                            'hover' => $button->hover,
                            'type' => 'primary',
                            'class' => '',
                        ])
                            {{ $button->label }}
                        @endcomponent
                    @endforeach
                </div>
            @endif
        </div>
        <div class="shrink-0 grow-0 basis-1/2">
            <div class="contact-map w-full aspect-w-1 aspect-h-1">
                @if ($row_data->image)
                    <img
                        src="{{ $row_data->image->getResizeUrl('text_image_2x', true) }}"
                        alt="{{ $row_data->image->alt }}"
                        class="object-cover"
                    >
                @endif
            </div>
        </div>
    </div>
</div>

