@php
  /**
   * $row_data consists of:
   *   ->row_label - string - Not relevant for current use-case
   *   ->row_id - string - for anchor tags in case they want to link with a # value
   *   ->buttons, Collection - items which consist of:
   *     ->label - string
   *     ->hover - string - if not set, use the label as the hover tooltip
   *     ->link - string
   *     ->target - string
   *     ->rel - string
   *   ->title - string
   */
  $row_data = \App\Crud\Rows\TextRow::unpack($row);
@endphp

<div class="container">
  <h2>TextRow content goes here</h2>
</div>
