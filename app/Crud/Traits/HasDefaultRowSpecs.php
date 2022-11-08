<?php

namespace App\Crud\Traits;

trait HasDefaultRowSpecs
{
    /**
     * Array of Site-wide default row specs.
     *
     * @return array
     */
    protected function defaultRowSpecs(): array
    {
        return [
            \App\Crud\Rows\EventHighlightsRow::make(),
            \App\Crud\Rows\GalleryRow::make(),
            \App\Crud\Rows\ImageRow::make(),
            \App\Crud\Rows\TextRow::make(),
        ];
    }
}
