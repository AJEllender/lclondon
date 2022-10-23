<?php

namespace App\Crud\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Yadda\Enso\Crud\Forms\Fields\SelectField;
use Yadda\Enso\Crud\Handlers\FlexibleRow;

trait HasBackgroundTextures
{
    /**
     * Field for selectingn Background Texture
     *
     * @param string $field_name
     *
     * @return SelectField
     */
    protected function backgroundTextureField(string $field_name = 'background_texture'): SelectField
    {
        return SelectField::make($field_name)
            ->setLabel('Background Texture')
            ->setDefaultValue($this->getDefaultBackgroundTextureValue())
            ->setOptions($this->getBackgroundTextureOptions())
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
    protected function getBackgroundTextureOptions(): array
    {
        if (!empty(Config::get('enso.flexible-content.rows.' . $this->getName() . '.background-textures'))) {
            $options = Config::get('enso.flexible-content.rows.' . $this->getName() . '.background-textures', []);
        } else {
            $options = Config::get('enso.flexible-content.options.background-textures', []);
        }

        return (new Collection($options))
            ->map(function ($item, $key) {
                return SelectField::makeOption($key, $item);
            })->values()->toArray();
    }

    /**
     * Default Background Texture
     *
     * @return string
     */
    protected function getDefaultBackgroundTexture(): string
    {
        if (!empty(Config::get('enso.flexible-content.rows.' . $this->getName() . '.default-background-texture'))) {
            return Config::get('enso.flexible-content.rows.' . $this->getName() . '.default-background-texture', 'none');
        } else {
            return Config::get('enso.flexible-content.options.default-background-texture', 'none');
        }
    }

    /**
     * Default values of the Background Texture dropdown box
     *
     * @return array
     */
    protected function getDefaultBackgroundTextureValue(): array
    {
        $default_background_texture = $this->getDefaultBackgroundTexture();

        if (!empty(Config::get('enso.flexible-content.rows.' . $this->getName() . '.background-textures'))) {
            $default_background_texture_name = Config::get('enso.flexible-content.rows.' . $this->getName() . '.background-textures.' . $default_background_texture, 'Light');
        } else {
            $default_background_texture_name = Config::get('enso.flexible-content.options.background-textures.' . $default_background_texture, 'None');
        }

        return SelectField::makeOption(
            $default_background_texture,
            $default_background_texture_name
        );
    }

    /**
     * Get the selected Background Texture from the row
     *
     * @param FlexibleRow $row
     * @param string $field_name
     *
     * @return string
     */
    protected function getSelectedBackgroundTexture(FlexibleRow $row, string $field_name = 'background_texture'): string
    {
        $instance = static::make();

        $track_by = $instance->getField($field_name)->getSetting('track_by');

        return Arr::get(
            $row->blockContent($field_name),
            $track_by,
            $instance->getDefaultBackgroundTexture()
        );
    }
}
