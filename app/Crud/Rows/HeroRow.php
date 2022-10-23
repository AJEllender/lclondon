<?php

namespace App\Crud\Rows;

use App\Crud\Fields\ButtonsField;
use App\Crud\Traits\HasBackgroundColors;
use App\Crud\Traits\HasBackgroundTextures;
use App\Crud\Traits\HasFontColors;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Yadda\Enso\Crud\Forms\Fields\FileUploadFieldResumable;
use Yadda\Enso\Crud\Forms\Fields\SelectField;
use Yadda\Enso\Crud\Forms\Fields\TextField;
use Yadda\Enso\Crud\Forms\Fields\WysiwygField;
use Yadda\Enso\Crud\Forms\FlexibleContentSection;
use Yadda\Enso\Crud\Handlers\FlexibleRow;

/**
 * Represents a purely text row withing a flexible content collection.
 */
class HeroRow extends FlexibleContentSection
{
    use HasBackgroundColors,
        HasBackgroundTextures,
        HasFontColors;

    /**
     * Default name for this section
     *
     * @param string
     */
    const DEFAULT_NAME = 'herorow';

    public function __construct(string $name = 'herorow')
    {
        parent::__construct($name);

        $this
            ->setLabel('Hero')
            ->excerptField('title')
            ->addFields([
                FileUploadFieldResumable::make('image')
                    ->addFieldsetClass('is-one-quarter'),
                static::backgroundTextureField()
                    ->addFieldsetClass('is-one-quarter'),
                static::backgroundColorField()
                    ->addFieldsetClass('is-one-quarter'),
                static::fontColorField()
                    ->addFieldsetClass('is-one-quarter'),
                TextField::make('title')
                    ->addFieldsetClass('is-two-thirds'),
                WysiwygField::make('content')
                    ->setLabel('Subtitle')
                    ->setModules(
                        Config::get('enso.flexible-content.rows.herorow.modules', [])
                    ),
                ButtonsField::make('buttons')
                    ->setMaxRows(2),
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
            'buttons' => static::flexibleFieldContent('buttons', $row),
            'background_color' => static::getSelectedBackgroundColor($row),
            'background_texture' => static::getSelectedBackgroundTexture($row),
            'content' => static::getWysiwygHtml($row->getBlocks(), 'content'),
            'font_color' => static::getSelectedFontColor($row),
            'image' => $row->blockContent('image')->first(),
            'title' => $row->blockContent('title'),
        ];
    }
}
