<?php

namespace App\Crud\Rows;

use App\Crud\Fields\EventHighlightsField;
use Yadda\Enso\Crud\Forms\Fields\TextField;
use Yadda\Enso\Crud\Forms\FlexibleContentSection;
use Yadda\Enso\Crud\Handlers\FlexibleRow;

/**
 * Represents a purely text row withing a flexible content collection.
 */
class EventHighlightsRow extends FlexibleContentSection
{
    /**
     * Default name for this section
     *
     * @param string
     */
    const DEFAULT_NAME = 'eventhighlights';

    /**
     * Create a new instance of TextRow
     */
    public function __construct(string $name = 'eventhighlights')
    {
        parent::__construct($name);

        $this
            ->excerptField('title')
            ->setLabel('Event Highlights')
            ->addFields([
                TextField::make('title'),
                EventHighlightsField::make('eventhighlights')
                    ->setLabel('Event Highlights'),
            ]);
    }

    /**
     * Unpack Row-specific fields.
     *
     * Should be overriden in Rowspecs that extend this class.
     */
    protected static function getRowContent(FlexibleRow $row): array
    {
        $instance = static::make();

        return [
            'title' => $row->blockContent('title'),
            'eventhighlights' => $instance->getFlexibleContentFieldContent('eventhighlights', $row),
        ];
    }
}
