<?php

namespace App\Crud\Handlers;

use App\Crud\Handlers\FlexibleRow;
use Illuminate\Support\Collection;
use Yadda\Enso\Crud\Handlers\FlexibleField as BaseClass;

class FlexibleField extends BaseClass
{
        /**
     * Loads the data into an interal array, passing it through a helper class
     * in order to expand the data
     *
     * @param array  $data       data to expand
     * @param string $base_class base class for bem
     *
     * @return self
     */
    public function loadData(array $data, $base_class)
    {
        $parsed_data = $this->parser->expand($data);

        $this->rows = collect($parsed_data)->reduce(function ($carry, $item) use ($base_class) {
            $row = new FlexibleRow($item, $base_class);

            // Set this row's previous row.
            $row->previous_row = $carry->last();

            // Set the previous row's next row
            if ($carry->last()) {
                $carry->last()->next_row = $row;
            }

            $carry->push($row);

            return $carry;
        }, new Collection);

        return $this;
    }
}
