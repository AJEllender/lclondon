<?php

namespace App\Crud\Rows;

use Yadda\Enso\Crud\Forms\Fields\TextField;
use Yadda\Enso\Crud\Forms\Fields\WysiwygField;
use Yadda\Enso\Crud\Forms\FlexibleContentSection;
use Yadda\Enso\Crud\Handlers\FlexibleRow;

/**
 * Represents a purely text row withing a flexible content collection.
 */
class NewsletterRow extends FlexibleContentSection
{
    /**
     * Default name for this section
     *
     * @param string
     */
    const DEFAULT_NAME = 'newsletter';

    /**
     * Create a new instance of Newsletter
     */
    public function __construct(string $name = 'newsletter')
    {
        parent::__construct($name);

        $this
            ->setLabel('Newsletter Signup')
            ->excerptField('title')
            ->addFields([
                TextField::make('title'),
                WysiwygField::make('content'),
                TextField::make('button_label')
                    ->addFieldsetClass('is-half is-offset-half'),
                TextField::make('thankyou_message')
                    ->setLabel('Thank you message on success.'),
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
            'button_label' => $row->blockContent('button_label'),
            'content' => static::getWysiwygHtml($row->getBlocks(), 'content'),
            'thankyou_message' => $row->blockContent('thankyou_message'),
            'title' => $row->blockContent('title'),
        ];
    }
}
