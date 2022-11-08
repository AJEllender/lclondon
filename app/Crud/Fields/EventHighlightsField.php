<?php

namespace App\Crud\Fields;

use App\Crud\Rows\EventHighlightRow;
use Yadda\Enso\Crud\Forms\Fields\FlexibleContentField;
use Yadda\Enso\Crud\Traits\FieldHasRowSpecs;

class EventHighlightsField extends FlexibleContentField
{
    use FieldHasRowSpecs;

    public function __construct(string $name = 'main')
    {
        parent::__construct($name);

        $this->addRowSpecs([
            EventHighlightRow::make(),
        ])->addRowLabel('Add Event');
    }
}
