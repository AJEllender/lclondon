<?php

namespace App\Crud\Rows;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\URL;
use Yadda\Enso\Crud\Forms\Fields\SelectField;
use Yadda\Enso\Crud\Forms\Fields\TextField;
use Yadda\Enso\Crud\Forms\FlexibleContentSection;
use Yadda\Enso\Crud\Handlers\FlexibleRow;

class ButtonRow extends FlexibleContentSection
{
    /**
     * Regex pattern to determins if a given url is a valid url.
     *
     * NOTE 1: This regex only works when using against strtolower-ed urls
     *
     * NOTE 2: Accomodates the notion of single label domains as being valid,
     *         although we may decide to prevent this in future.
     */
    const URL_REGEX = '^((https?:)?\/\/)?(www\.)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*(\.[a-z]{2,5})?(:[0-9]{1,5})?(\/.*)?';

    /**
     * Default name for this section
     *
     * @param string
     */
    const DEFAULT_NAME = 'buttonrow';

    public function __construct(string $name = 'buttonrow')
    {
        parent::__construct($name);

        $this->setLabel('Button')
            ->excerptField('label')
            ->addFields([
                TextField::make('label')
                    ->setLabel('Text')
                    ->addFieldsetClass('is-6'),
                TextField::make('hover')
                    ->setLabel('Hover Text')
                    ->addFieldsetClass('is-6'),
                TextField::make('link')
                    ->setLabel('URL')
                    ->addFieldsetClass('is-9'),
                SelectField::make('target')
                    ->setLabel('Open in')
                    ->setOptions([
                        'auto' => 'Automatic',
                        '_self' => 'This window',
                        '_blank' => 'A new window',
                    ])
                    ->setSettings([
                        'allow_empty' => false,
                        'show_labels' => false,
                    ])
                    ->setDefaultValue(SelectField::makeOption('auto', 'Automatic'))
                    ->addFieldsetClass('is-3'),
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
        $url = static::parseUrl($row->blockContent('link'));

        $track_by = static::make()->getField('target')->getSetting('track_by');

        $target = Arr::get($row->blockContent('target'), $track_by, 'auto') !== 'auto'
            ? Arr::get($row->blockContent('target'), $track_by, '_blank')
            : static::determineTarget($url);

        return [
            'label' => $row->blockContent('label'),
            'hover' => $row->blockContent('hover'),
            'link' => $url,
            'target' => $target,
            'rel' => $target === '_blank'
                ? 'noopener noreferrer'
                : '',
        ];
    }

    /**
     * Determine whether the target of the given url should be _self or _blank
     *
     * @param string $url
     *
     * @return string
     */
    protected static function determineTarget(string $url): string
    {
        if (strlen($url) === 1) {
            return '_self';
        }

        return ((substr($url, 0, 1) !== "/") || (substr($url, 1, 1) === '/'))
            ? '_blank'
            : '_self';
    }

    /**
     * Parses the given url, determining whether it is either an external url (
     * at which point it is returned as such) or an internal utl
     *
     * @param string $url
     *
     * @return string
     */
    protected static function parseUrl(string $url): string
    {
        preg_match('#(?:https?:\/\/)(.*)#', URL::to('/'), $matches);

        $local_domain = Arr::get($matches, '1', '');

        if (preg_match('#' . static::URL_REGEX . '#', $url)) {
            preg_match('#^(?:(?:https?:)?\/\/)?' . strtolower($local_domain) . '(.*)#', strtolower($url), $matches);

            if (isset($matches[1])) {
                return '/' . trim($matches[1], '/');
            } else {
                return $url;
            }
        } else {
            return '/' . trim($url, '/');
        }
    }
}
