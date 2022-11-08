<?php

namespace App\Crud\Traits;

use Exception;
use Illuminate\Support\Arr;
use Yadda\Enso\Crud\Forms\FieldInterface;
use Yadda\Enso\Crud\Forms\Fields\SelectField;
use Yadda\Enso\Crud\Handlers\FlexibleRow;

trait HasAlignment
{
    /**
     * An Alignment select field
     *
     * @param string $field_name
     *
     * @return FieldInterface
     */
    protected static function alignmentField(string $field_name = 'alignment'): FieldInterface
    {
        return SelectField::make($field_name)
            ->setLabel('Media Alignment')
            ->setHelpText('Note: this will only affect layout on desktop')
            ->setOptions([
                'auto'  => 'Automatic',
                'left'  => 'Left',
                'right' => 'Right',
            ])
            ->setDefaultValue(SelectField::makeOption('auto', 'Automatic'))
            ->setSettings([
                'allow_empty' => false,
                'show_labels' => false,
            ]);
    }

    /**
     * Calculates whether this should be a right or left aligned Image Text rows
     *
     * @param FlexibleRow $row
     *
     * @return string
     */
    protected static function calculateAlignment(FlexibleRow $row, string $field_name = 'alignment')
    {
        $track_by = static::make()->getField($field_name)->getSetting('track_by');

        try {
            $requested_alignment = Arr::get($row->blockContent($field_name), $track_by, 'auto');
        } catch (Exception $e) {
            return 'left';
        }

        if ($requested_alignment !== 'auto') {
            return $requested_alignment;
        }

        $state = [];
        $working_row = $row;

        // Pluck out all previous Alignable rows with the same property
        do {
            if ($working_row->previous_row ? ($working_row->blockContent($field_name) ?? null) : null) {
                array_unshift($state, ($working_row->blockContent($field_name)[$track_by] ?? 'auto'));
            }

            $working_row = $working_row->previous_row;
        } while ($working_row);

        // Work down the list, calculating the final left or right value.
        return !empty($state)
            ? array_reduce($state, function ($carry, $item) {
                if ($item !== 'auto') {
                    return $item;
                }

                if (is_null($carry)) {
                    return 'left';
                }

                if ($carry === 'left') {
                    return 'right';
                } else {
                    return 'left';
                }
            })
            : 'left';
    }

    /**
     * Collection of row names that should function together when determining
     * automatic alignment.
     *
     * @return array
     */
    protected static function rowHasAlignmentRowMatches(): array
    {
        return [
            static::make()->getName(),
        ];
    }
}
