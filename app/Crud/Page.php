<?php

namespace App\Crud;

use Yadda\Enso\Pages\Crud\Page as BaseCrud;

class Page extends BaseCrud
{
    /**
     * Array of Site-wide default row specs.
     *
     * @return array
     */
    protected function defaultRowSpecs(): array
    {
        return [
            \App\Crud\Rows\TextRow::make(),
        ];
    }
}
