<?php

namespace App\Crud\Rows;

use App\Crud\Traits\HasAlignment;
use Illuminate\Support\Arr;
use Yadda\Enso\Crud\Forms\Fields\SelectField;
use Yadda\Enso\Crud\Forms\FlexibleContentSection;
use Yadda\Enso\Crud\Handlers\FlexibleRow;
use Yadda\Enso\Facades\EnsoCrud;

/**
 * Represents a purely text row withing a flexible content collection.
 */
class EventHighlightRow extends FlexibleContentSection
{
    use HasAlignment;

    /**
     * Default name for this section
     *
     * @param string
     */
    const DEFAULT_NAME = 'eventhighlight';

    /**
     * Create a new instance of TextRow
     */
    public function __construct(string $name = 'eventhighlight')
    {
        parent::__construct($name);

        $this
            ->setLabel('Event Highlight')
            ->addFields([
                SelectField::make('event')
                    ->useAjax(
                        route('admin.events.index'),
                        EnsoCrud::modelClass('event')
                    )
                    ->setSettings([
                        'order' => 'desc',
                        'orderby' => 'start_at',
                    ])
                    ->addFieldsetClass('is-three-quarters'),
                static::alignmentField()
                    ->setLabel('Image alignment')
                    ->addFieldsetClass('is-3')
            ]);
    }

    /**
     * Unpack Row-specific fields.
     *
     * Should be overriden in Rowspecs that extend this class.
     */
    protected static function getRowContent(FlexibleRow $row): array
    {
        $event_id = Arr::get($row->blockContent('event'), 'id');

        return [
            'alignment' => static::calculateAlignment($row),
            'event' => $event_id ? EnsoCrud::query('event')
                ->with('eventType')
                ->find($event_id) : null,
        ];
    }
}
