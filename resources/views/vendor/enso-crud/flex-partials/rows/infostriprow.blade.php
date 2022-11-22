@php
  /**
   * $row_data consists of:
   *   ->row_id - string
   *   ->row_label - string
   *   ->row_type - string
   *   ->buttons, Collection - items which consist of:
   *     ->label - string
   *     ->hover - string - if not set, use the label as the hover tooltip
   *     ->link - string
   *     ->target - string
   *     ->rel - string
   *   ->color - string - 'blue-300|blue-500|blue-700|blue-900|red-300|red-500|red-700|red-900'
   *   ->title - string
   */
  $row_data = $row->unpack();
@endphp

<div
    data-label="{{ $row_data->row_label }}"
    id="{{  $row_data->row_id }}"
    class="relative w-full flex items-center justify-center font-bold text-base md:text-lg lg:text-xl py-2.5 px-6 sm:px-12 bg-{{ $row_data->color }} text-white"
>
    {{ $row_data->title }}
</div>

