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
            \App\Crud\Rows\CalendarRow::make(),
            \App\Crud\Rows\ContactRow::make(),
            \App\Crud\Rows\EventHighlightsRow::make(),
            \App\Crud\Rows\GalleryRow::make(),
            \App\Crud\Rows\ImageRow::make(),
            \App\Crud\Rows\NewsletterRow::make(),
            \App\Crud\Rows\TextImageRow::make(),
            \App\Crud\Rows\TextRow::make(),
            \App\Crud\Rows\UpcomingEventTypesRow::make(),
        ];
    }
}
