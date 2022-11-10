<?php

namespace App\Crud\Rows;

use Yadda\Enso\Crud\Forms\Fields\TextField;
use Yadda\Enso\Crud\Forms\FlexibleContentSection;
use Yadda\Enso\Crud\Handlers\FlexibleRow;
use Yadda\Enso\Facades\EnsoCrud;

class UpcomingEventTypesRow extends FlexibleContentSection
{
    /**
     * Default name for this section
     *
     * @param string
     */
    const DEFAULT_NAME = 'upcomingeventtypesrow';

    public function __construct(string $name = 'upcomingeventtypesrow')
    {
        parent::__construct($name);

        $this->setLabel('Upcoming Event Types')
            ->excerptField('title')
            ->addFields([
                TextField::make('title')
                    ->setLabel('Title'),
            ]);
    }

    /**
     * Unpack Row-specific fields.
     *
     * @param FlexibleRow $row
     *
     * @return array
     */
    protected static function getRowContent(FlexibleRow $row): array
    {
        return [
            'event_types' => EnsoCrud::query('eventtype')
                ->withFutureEvents()
                ->orderByEventDates()
                ->with([
                    'events' => function ($query) {
                        $query->upcoming()->orderBy('start_at', 'asc');
                    },
                ])
                ->get(),
            'title' => $row->blockContent('title'),
        ];
    }
}
