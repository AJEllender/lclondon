<?php

namespace App\Crud\Traits;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Yadda\Enso\Crud\Forms\FieldInterface;
use Yadda\Enso\Crud\Forms\Fields\SelectField;
use Yadda\Enso\Crud\Handlers\FlexibleRow;

trait HasAlignment
{
    /**
     * Calculates whether this should be a right or left aligned Image Text rows
     *
     * @param FlexibleRow $row
     *
     * @return string
     */
    protected static function hasAlignmentCalculate(FlexibleRow $row, string $field_name = 'alignment', string $type = 'left-right')
    {
        try {
            $requested_alignment = Arr::get(
                $row->blockContent($field_name),
                static::hasAlignmentGetTrackBy($field_name),
                'auto'
            );
        } catch (Exception $e) {
            return 'left';
        }

        if ($requested_alignment !== 'auto') {
            return $requested_alignment;
        }

        $alignments = static::hasAlignmentGetPreviousAlignments($row, $field_name, $type);

        return !empty($alignments)
            ? array_reduce($alignments, function ($carry, $item) {
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
     * Gets the default alignment option for this row.
     *
     * @return array
     */
    protected static function hasAlignmentGetDefaultOption(): array
    {
        $default = static::hasAlignmentGetDefaultKey();

        return SelectField::makeOption(
            $default,
            Arr::get(static::hasAlignmentGetOptions(), $default, 'Unset')
        );
    }

    /**
     * Gets the default alignment option for this row.
     *
     * @return array
     */
    protected static function hasAlignmentGetDefaultKey(): string
    {
        return Config::get('enso.flexible-content.alignments.defaults.' . static::hasAlignmentGetType());
    }

    /**
     * An Alignment select field
     *
     * @param string $field_name
     *
     * @return FieldInterface
     */
    protected static function hasAlignmentGetField(): FieldInterface
    {
        return SelectField::make(static::hasAlignmentGetFieldName())
            ->setLabel('Media Alignment')
            ->setHelpText('Note: this will only affect layout on desktop')
            ->setOptions(static::hasAlignmentGetOptions())
            ->setDefaultValue(static::hasAlignmentGetDefaultOption())
            ->setSettings([
                'allow_empty' => false,
                'show_labels' => false,
            ]);
    }

    /**
     * Get the alignment type of this row.
     *
     * @return string
     */
    protected static function hasAlignmentGetFieldName(): string
    {
        return 'alignment';
    }

    /**
     * Collection of row names that should function together when determining
     * automatic alignment.
     *
     * @return array
     */
    protected static function hasAlignmentGetMatchingRowTypes(): array
    {
        return Config::get(
            'enso.flexible-content.alignments.types.' . static::hasAlignmentGetType(),
            []
        );
    }

    /**
     * Gets the Alignment options for this row.
     *
     * @return array
     */
    protected static function hasAlignmentGetOptions(): array
    {
        return Config::get(
            'enso.flexible-content.alignments.options.' . static::hasAlignmentGetType(),
            []
        );
    }

    /**
     * Pulls alignment array from this and previous rows.
     *
     * @param FlexibleRow $row
     *
     * @return array
     */
    protected static function hasAlignmentGetPreviousAlignments(FlexibleRow $row): array
    {
        $field_name = static::hasAlignmentGetFieldName();
        $state = [];
        $track_by = static::hasAlignmentGetTrackBy($field_name);
        $type = static::hasAlignmentGetType();
        $working_row = $row;

        do {
            if (static::hasAlignmentIsAlignableRow($working_row, $type) && ($working_row->blockContent($field_name) ?? null)) {
                array_unshift($state, ($working_row->blockContent($field_name)[$track_by] ?? 'auto'));
            }

            $working_row = $working_row->previous_row;
        } while ($working_row);

        return $state;
    }

    /**
     * Gets the Track by name for the alignment selection field.
     *
     * @param string $field_name
     *
     * @return string
     */
    protected static function hasAlignmentGetTrackBy(string $field_name = 'alignment'): string
    {
        return static::make()->getField($field_name)->getSetting('track_by');
    }

    /**
     * Get the alignment type of this row.
     *
     * @return string
     */
    protected static function hasAlignmentGetType(): string
    {
        return 'left-right';
    }

    /**
     * Whether the given row is an alignable row of the specified type
     *
     * @param FlexibleRow $row
     *
     * @return boolean
     */
    protected static function hasAlignmentIsAlignableRow(FlexibleRow $row): bool
    {
        return in_array(
            $row->getType(),
            static::hasAlignmentGetMatchingRowTypes()
        );
    }
}
