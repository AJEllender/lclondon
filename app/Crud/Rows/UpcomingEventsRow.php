<?php

namespace App\Crud\Rows;

use Illuminate\Support\Arr;
use Yadda\Enso\Crud\Forms\Fields\SelectField;
use Yadda\Enso\Crud\Forms\Fields\TextField;
use Yadda\Enso\Crud\Forms\FlexibleContentSection;
use Yadda\Enso\Crud\Handlers\FlexibleRow;
use Yadda\Enso\Facades\EnsoCrud;

class UpcomingEventsRow extends FlexibleContentSection
{
    /**
     * Default name for this section
     *
     * @param string
     */
    const DEFAULT_NAME = 'upcomingeventsrow';

    public function __construct(string $name = 'upcomingeventsrow')
    {
        parent::__construct($name);

        $this->setLabel('Upcoming Events')
            ->excerptField('title')
            ->addFields([
                TextField::make('title')
                    ->setLabel('Title'),
                SelectField::make('event_type')
                    ->useAjax(route('admin.event-types.index'), EnsoCrud::modelClass('eventtype'))
                    ->setHelpText('Leave empty to show the latest 3 events of ANY type.'),
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
        $instance = new static;

        $event_type_id = Arr::get($row->blockContent('event_type'), $instance->getField('event_type')->getSetting('track_by'), null);

        $event_type = $event_type_id ? EnsoCrud::query('eventtype')->find($event_type_id) : null;

        $event_model_class = EnsoCrud::modelClass('event');

        return [
            'event_type' => $event_type,
            'events' => $event_model_class::query('event')
                /**
                 * If parent model this row is on is an Event, exclude it from
                 * the upcoming events list
                 */
                ->when($row->instance() instanceof $event_model_class, function ($query) use ($row) {
                    $query->where($row->instance()->getQualifiedKeyName(), '!=', $row->instance()->getKey());
                })
                ->when($event_type, function ($query) use ($event_type) {
                    $query->forEventType($event_type);
                })
                ->orderBy('start_at', 'asc')
                ->upcoming()
                ->take(3)
                ->get(),
            'title' => $row->blockContent('title'),
        ];
    }
}
