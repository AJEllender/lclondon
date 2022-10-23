<?php

namespace App\Crud\Rows;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Yadda\Enso\Crud\Forms\Fields\ButtonsField;
use Yadda\Enso\Crud\Forms\Fields\FileUploadFieldResumable;
use Yadda\Enso\Crud\Forms\Fields\SelectField;
use Yadda\Enso\Crud\Forms\Fields\TextField;
use Yadda\Enso\Crud\Forms\FlexibleContentSection;
use Yadda\Enso\Crud\Handlers\FlexibleRow;

/**
 * Represents a purely text row withing a flexible content collection.
 */
class ImageRow extends FlexibleContentSection
{
    /**
     * Default name for this section
     *
     * @param string
     */
    const DEFAULT_NAME = 'imagerow';

    /**
     * Create a new instance of ImageRow
     */
    public function __construct(string $name = 'imagerow')
    {
        parent::__construct($name);

        $this
            ->setLabel('Image')
            ->excerptField('title')
            ->addFields([
                TextField::make('title')
                    ->addFieldsetClass('is-three-quarters'),
                SelectField::make('style')
                    ->setDefaultValue(static::getDefaultStyleValue())
                    ->setOptions(static::getStyleOptions())
                    ->setSettings([
                        'allow_empty' => false,
                        'show_labels' => false,
                    ])
                    ->addFieldsetClass('is-one-quarter'),
                FileUploadFieldResumable::make('image'),
            ]);
    }

    /**
     * Default Style
     *
     * @return string
     */
    protected static function getDefaultStyle(): string
    {
        return Config::get('enso.flexible-content.rows.imagerow.default-style', 'wide');
    }

    /**
     * Default values of the Style dropdown box
     *
     * @return array
     */
    protected static function getDefaultStyleValue(): array
    {
        $default_style = static::getDefaultStyle();

        return SelectField::makeOption(
            $default_style,
            Config::get('enso.flexible-content.rows.imagerow.styles.' . $default_style, 'Wide')
        );
    }

    /**
     * Unpack Row-specific fields.
     *
     * Should be overriden in Rowspecs that extend this class.
     */
    protected static function getRowContent(FlexibleRow $row): array
    {
        $style_track_by = static::make()->getField('style')->getSetting('track_by');

        return [
            'image' => $row->blockContent('image')->first(),
            'style' => Arr::get(
                $row->blockContent('style'),
                $style_track_by,
                static::getDefaultStyle()
            ),
            'title' => $row->blockContent('title'),
        ];
    }

    /**
     * Options for the Style dropdown field
     *
     * @return array
     */
    protected static function getStyleOptions(): array
    {
        return (new Collection(Config::get('enso.flexible-content.rows.imagerow.styles', [])))
            ->map(function ($item, $key) {
                return SelectField::makeOption($key, $item);
            })->values()->toArray();
    }
}
