<?php

namespace App\Crud\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Yadda\Enso\Crud\Forms\Fields\SelectField;
use Yadda\Enso\Crud\Handlers\FlexibleRow;

trait HasFontColors
{
    /**
     * Field for selectingn Font Color
     *
     * @param string $field_name
     *
     * @return SelectField
     */
    protected function fontColorField(string $field_name = 'font_color'): SelectField
    {
        return SelectField::make($field_name)
            ->setLabel('Font Colour')
            ->setDefaultValue($this->getDefaultFontColorValue())
            ->setOptions($this->getFontColorOptions())
            ->setSettings([
                'allow_empty' => false,
                'show_labels' => false,
            ]);
    }

    /**
     * Options for the Style dropdown field
     *
     * @return array
     */
    protected function getFontColorOptions(): array
    {
        if (!empty(Config::get('enso.flexible-content.rows.' . $this->getName() . '.font-colors'))) {
            $options = Config::get('enso.flexible-content.rows.' . $this->getName() . '.font-colors', []);
        } else {
            $options = Config::get('enso.flexible-content.options.font-colors', []);
        }

        return (new Collection($options))
            ->map(function ($item, $key) {
                return SelectField::makeOption($key, $item);
            })->values()->toArray();
    }

    /**
     * Default Font Color
     *
     * @return string
     */
    protected function getDefaultFontColor(): string
    {
        if (!empty(Config::get('enso.flexible-content.rows.' . $this->getName() . '.default-font-color'))) {
            return Config::get('enso.flexible-content.rows.' . $this->getName() . '.default-font-color', 'dark');
        } else {
            return Config::get('enso.flexible-content.options.default-font-color', 'dark');
        }
    }

    /**
     * Default values of the Font Color dropdown box
     *
     * @return array
     */
    protected function getDefaultFontColorValue(): array
    {
        $default_font_color = $this->getDefaultFontColor();

        if (!empty(Config::get('enso.flexible-content.rows.' . $this->getName() . '.font-colors'))) {
            $default_font_color_name = Config::get('enso.flexible-content.rows.' . $this->getName() . '.font-colors.' . $default_font_color, 'Dark');
        } else {
            $default_font_color_name = Config::get('enso.flexible-content.options.font-colors.' . $default_font_color, 'Dark');
        }

        return SelectField::makeOption(
            $default_font_color,
            $default_font_color_name
        );
    }

    /**
     * Get the selected Font Color from the row
     *
     * @param FlexibleRow $row
     * @param string $field_name
     *
     * @return string
     */
    protected function getSelectedFontColor(FlexibleRow $row, string $field_name = 'font_color'): string
    {
        $instance = static::make();

        $track_by = $instance->getField($field_name)->getSetting('track_by');

        return Arr::get(
            $row->blockContent($field_name),
            $track_by,
            $instance->getDefaultFontColor()
        );
    }
}
