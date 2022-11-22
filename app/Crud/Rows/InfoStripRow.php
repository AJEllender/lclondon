<?php

namespace App\Crud\Rows;

use App\Crud\Contracts\AppliesDiminishedMargins;
use App\Crud\Fields\ButtonsField;
use Illuminate\Support\Arr;
use Yadda\Enso\Crud\Forms\Fields\SelectField;
use Yadda\Enso\Crud\Forms\Fields\TextField;
use Yadda\Enso\Crud\Forms\FlexibleContentSection;
use Yadda\Enso\Crud\Handlers\FlexibleRow;

/**
 * Represents a purely text row withing a flexible content collection.
 */
class InfoStripRow extends FlexibleContentSection implements AppliesDiminishedMargins
{
    /**
     * Default name for this section
     *
     * @param string
     */
    const DEFAULT_NAME = 'infostriprow';

    /**
     * Create a new instance of TextRow
     */
    public function __construct(string $name = 'infostriprow')
    {
        parent::__construct($name);

        $this
            ->setLabel('Info Strip')
            ->excerptField('title')
            ->addFields([
                TextField::make('title')
                    ->addFieldsetClass('is-three-quarters'),
                SelectField::make('color')
                    ->setOptions([
                        'blue-400' => 'Light Blue',
                        'blue-500' => 'Mid Blue',
                        'blue-700' => 'Dark Blue',
                        'blue-900' => 'Midnight Blue',
                        'red-400' => 'Light Red',
                        'red-500' => 'Mid Red',
                        'red-700' => 'Dark Red',
                        'red-900' => 'Deep Red',
                    ])->setDefaultValue(
                        SelectField::makeOption('blue-500', 'Mid Blue')
                    )->setSettings([
                        'allow_empty' => false,
                        'show_labels' => false,
                    ])
                    ->addFieldsetClass('is-one-quarter'),
                ButtonsField::make('buttons')
                    ->setHelpText('Adding a button to this row will NOT add a button, but will use the button content to turn the strip into a link.')
                    ->setMaxRows(1),
            ]);
    }

    /**
     * Unpack Row-specific fields.
     *
     * Should be overriden in Rowspecs that extend this class.
     */
    protected static function getRowContent(FlexibleRow $row): array
    {
        $instance = new static;

        return [
            'buttons' => static::make()->getFlexibleContentFieldContent('buttons', $row),
            'color' => $row->block('color') ? Arr::get(
                $row->blockContent('color'),
                $instance->getField('color')->getSetting('track_by'),
                'blue-500'
            ) : 'blue-500',
            'title' => $row->blockContent('title'),
        ];
    }
}
