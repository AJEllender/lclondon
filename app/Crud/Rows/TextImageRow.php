<?php

namespace App\Crud\Rows;

use App\Crud\Fields\ButtonsField;
use App\Crud\Traits\HasAlignment;
use Illuminate\Support\Facades\Config;
use Yadda\Enso\Crud\Forms\Fields\FileUploadFieldResumable;
use Yadda\Enso\Crud\Forms\Fields\TextField;
use Yadda\Enso\Crud\Forms\Fields\WysiwygField;
use Yadda\Enso\Crud\Forms\FlexibleContentSection;
use Yadda\Enso\Crud\Handlers\FlexibleRow;

/**
 * Represents a purely text row withing a flexible content collection.
 */
class TextImageRow extends FlexibleContentSection
{
    use HasAlignment;

    /**
     * Default name for this section
     *
     * @param string
     */
    const DEFAULT_NAME = 'textimagerow';

    /**
     * Create a new instance of TextRow
     */
    public function __construct(string $name = 'textimagerow')
    {
        parent::__construct($name);

        $this
            ->setLabel('Text Image')
            ->excerptField('title')
            ->addFields([
                FileUploadFieldResumable::make('images'),
                TextField::make('title')
                    ->addFieldsetClass('is-three-quarters'),
                $this->hasAlignmentGetField()
                    ->setLabel('Image alignment')
                    ->addFieldsetClass('is-3'),
                WysiwygField::make('content')
                    ->setModules(
                        Config::get('enso.flexible-content.rows.' . $name . '.modules', [])
                    ),
                ButtonsField::make('buttons')
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
        return [
            'alignment' => static::hasAlignmentCalculate($row),
            'buttons' => static::make()->getFlexibleContentFieldContent('buttons', $row),
            'content' => static::getWysiwygHtml($row->getBlocks(), 'content'),
            'image' => $row->blockContent('images') ? $row->blockContent('images')->first() : null,
            'title' => $row->blockContent('title'),
        ];
    }
}
