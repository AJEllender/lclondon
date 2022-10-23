<?php

namespace App\Crud\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Yadda\Enso\Crud\Forms\Fields\SelectField;
use Yadda\Enso\Crud\Handlers\FlexibleRow;

trait HasBackgroundColors
{
    /**
     * Field for selectingn Background Color
     *
     * @param string $field_name
     *
     * @return SelectField
     */
    protected function backgroundColorField(string $field_name = 'background_color'): SelectField
    {
        return SelectField::make($field_name)
            ->setLabel('Background Colour')
            ->setDefaultValue($this->getDefaultBackgroundColorValue())
            ->setOptions($this->getBackgroundColorOptions())
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
    protected function getBackgroundColorOptions(): array
    {
        if (!empty(Config::get('enso.flexible-content.rows.' . $this->getName() . '.background-colors'))) {
            $options = Config::get('enso.flexible-content.rows.' . $this->getName() . '.background-colors', []);
        } else {
            $options = Config::get('enso.flexible-content.options.background-colors', []);
        }

        return (new Collection($options))
            ->map(function ($item, $key) {
                return SelectField::makeOption($key, $item);
            })->values()->toArray();
    }

    /**
     * Default Background Color
     *
     * @return string
     */
    protected function getDefaultBackgroundColor(): string
    {
        if (!empty(Config::get('enso.flexible-content.rows.' . $this->getName() . '.default-background-color'))) {
            return Config::get('enso.flexible-content.rows.' . $this->getName() . '.default-background-color', 'light');
        } else {
            return Config::get('enso.flexible-content.options.default-background-color', 'light');
        }
    }

    /**
     * Default values of the Background Color dropdown box
     *
     * @return array
     */
    protected function getDefaultBackgroundColorValue(): array
    {
        $default_background_color = $this->getDefaultBackgroundColor();

        if (!empty(Config::get('enso.flexible-content.rows.' . $this->getName() . '.background-colors'))) {
            $default_background_color_name = Config::get('enso.flexible-content.rows.' . $this->getName() . '.background-colors.' . $default_background_color, 'Light');
        } else {
            $default_background_color_name = Config::get('enso.flexible-content.options.background-colors.' . $default_background_color, 'Light');
        }

        return SelectField::makeOption(
            $default_background_color,
            $default_background_color_name
        );
    }

    /**
     * Get the selected Background Color from the row
     *
     * @param FlexibleRow $row
     * @param string $field_name
     *
     * @return string
     */
    protected function getSelectedBackgroundColor(FlexibleRow $row, string $field_name = 'background_color'): string
    {
        $instance = static::make();

        $track_by = $instance->getField($field_name)->getSetting('track_by');

        return Arr::get(
            $row->blockContent($field_name),
            $track_by,
            $instance->getDefaultBackgroundColor()
        );
    }
}
