<?php

namespace App\Crud\Rows;

use App\Crud\Fields\ButtonsField;
use Illuminate\Support\Facades\Config;
use Yadda\Enso\Crud\Forms\Fields\TextField;
use Yadda\Enso\Crud\Forms\Fields\WysiwygField;
use Yadda\Enso\Crud\Forms\FlexibleContentSection;
use Yadda\Enso\Crud\Handlers\FlexibleRow;

/**
 * Represents a purely text row withing a flexible content collection.
 */
class TextRow extends FlexibleContentSection
{
    /**
     * Default name for this section
     *
     * @param string
     */
    const DEFAULT_NAME = 'textrow';

    /**
     * Create a new instance of TextRow
     */
    public function __construct(string $name = 'textrow')
    {
        parent::__construct($name);

        $this
            ->setLabel('Text')
            ->excerptField('title')
            ->addFields([
                TextField::make('title'),
                WysiwygField::make('content')
                    ->setModules(
                        Config::get('enso.flexible-content.rows.herorow.modules', [])
                    ),
                ButtonsField::make('buttons'),
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
            'buttons' => static::make()->getFlexibleContentFieldContent('buttons', $row),
            'content' => static::getWysiwygHtml($row->getBlocks(), 'content'),
            'title' => $row->blockContent('title'),
        ];
    }
}
