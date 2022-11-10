<?php

namespace App\Crud\Rows;

use Illuminate\Support\Arr;
use Yadda\Enso\Crud\Forms\Fields\SelectField;
use Yadda\Enso\Crud\Forms\Fields\TextField;
use Yadda\Enso\Crud\Forms\FlexibleContentSection;
use Yadda\Enso\Crud\Handlers\FlexibleRow;
use Yadda\Enso\Facades\EnsoCrud;

class CalendarRow extends FlexibleContentSection
{
    /**
     * Default name for this section
     *
     * @param string
     */
    const DEFAULT_NAME = 'calendarrow';

    public function __construct(string $name = 'calendarrow')
    {
        parent::__construct($name);

        $this->setLabel('Calendar')
            ->excerptField('title')
            ->addFields([
                TextField::make('title')
                    ->setLabel('Title')
                    ->addFieldsetClass('is-half'),
                SelectField::make('event_type')
                    ->setLabel('Event Type')
                    ->setOptions(EnsoCrud::query('eventtype')
                        ->get()
                        ->pluck('name', 'id')
                    )
                    ->setHelpText('If you pick an Event Type here, the calendar will only show events of that type. Otherwise, it will show all events.')
                    ->addFieldsetClass('is-half'),
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
        $track_by = (new static)->getField('event_type')->getSetting('track_by');
        $category_id = Arr::get($row->blockContent('event_type'), $track_by);

        return [
            'event_type' => $category_id ? EnsoCrud::query('eventtype')->find($category_id) : null,
            'title' => $row->blockContent('title'),
        ];
    }
}
