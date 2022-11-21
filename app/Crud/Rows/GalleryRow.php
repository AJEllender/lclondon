<?php

namespace App\Crud\Rows;

use Illuminate\Support\Arr;
use Yadda\Enso\Crud\Forms\Fields\FileUploadFieldResumable;
use Yadda\Enso\Crud\Forms\Fields\SelectField;
use Yadda\Enso\Crud\Forms\Fields\TextField;
use Yadda\Enso\Crud\Forms\FlexibleContentSection;
use Yadda\Enso\Crud\Handlers\FlexibleRow;

/**
 * Represents a purely text row withing a flexible content collection.
 */
class GalleryRow extends FlexibleContentSection
{
    /**
     * Default name for this section
     *
     * @param string
     */
    const DEFAULT_NAME = 'galleryrow';

    /**
     * Create a new instance of GalleryRow
     */
    public function __construct(string $name = 'galleryrow')
    {
        parent::__construct($name);

        $this
            ->setLabel('Gallery')
            ->addFields([
                TextField::make('title')
                    ->addFieldsetClass('is-three-quarters'),
                SelectField::make('per_row')
                    ->setLabel('Images Per Row')
                    ->setOptions([
                        3 => '3',
                        4 => '4',
                        6 => '6',
                    ])
                    ->setDefaultValue([
                        SelectField::makeOption(4, 4)
                    ])
                    ->setSettings([
                        'allow_empty' => false,
                        'show_label' => false,
                    ])
                    ->addFieldsetClass('is-one-quarter'),
                FileUploadFieldResumable::make('images')
                    ->setMaxFiles(-1),
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
            'images' => $row->blockContent('images'),
            'per_row' => $row->block('per_row')
                ? Arr::get($row->blockContent('per_row'), $instance->getField('per_row')->getSetting('track_by'), 4)
                : 4,
            'title' => $row->blockContent('title'),
        ];
    }
}
