<?php

namespace App\Crud\Rows;

use App\Utilities\Location;
use Yadda\Enso\Crud\Forms\Fields\TextField;
use Yadda\Enso\Crud\Forms\FlexibleContentSection;
use Yadda\Enso\Crud\Handlers\FlexibleRow;

class ContactRow extends FlexibleContentSection
{
    /**
     * Default name for this section
     *
     * @param string
     */
    const DEFAULT_NAME = 'contactrow';

    public function __construct(string $name = 'contactrow')
    {
        parent::__construct($name);

        $this->setLabel('Contact')
            ->excerptField('title')
            ->addFields([
                TextField::make('title')
                    ->setLabel('Title'),
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
            'location' => new Location,
            'title' => $row->blockContent('title'),
        ];
    }
}
