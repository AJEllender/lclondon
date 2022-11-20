@php
  /**
   * $row_data consists of:
   *   ->row_label - string - Not relevant for current use-case
   *   ->row_id - string - for anchor tags in case they want to link with a # value
   *   ->row_type - string - 'newsletter'
   *   ->button_label - string
   *   ->content - string - HTML
   *   ->thankyou_message - string
   *   ->title - string
   */
  $row_data = $row->unpack();
@endphp

<div
    data-label="{{ $row_data->row_label }}"
    id="{{  $row_data->row_id }}"
    class="relative w-full flex items-center justify-center py-10 sm:py-16 md:py-20 lg:py-24 px-6 sm:px-12 bg-gradient-to-b from-orange-300 via-orange-200 to-orange-300"
>
    <div class="w-full md:max-w-3xl">
        @if (!empty($row_data->title))
            @component('enso-crud::flex-partials.components.title', [
                'class' => 'mb-4 lg:mb-6 text-center'
            ])
                {{ $row_data->title }}
            @endcomponent
        @endif

        @if (!empty($row_data->content))
            @component('enso-crud::flex-partials.components.content', [
                'class' => 'prose prose-lg text-center'
            ])
                {!! $row_data->content !!}
            @endcomponent
        @endif

        <newsletter-signup-form
            classes="mt-4 md:mt-6 lg:mt-8"
            csrf-token="{{ Session::token() }}"
            success-message="{{ $row_data->thankyou_message }}"
        >
        </newsletter-signup-form>
    </div>
</div>
