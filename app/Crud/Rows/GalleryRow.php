<?php

namespace App\Crud\Rows;

use Yadda\Enso\Crud\Forms\Fields\FileUploadFieldResumable;
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
                TextField::make('title'),
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
        return [
            'images' => $row->blockContent('images'),
            'title' => $row->blockContent('title'),
        ];
    }
}
