<?php

namespace App\Crud\Fields;

use Yadda\Enso\Crud\Forms\Fields\FlexibleContentField;

class HeaderField extends FlexibleContentField
{
    public function __construct(string $name = 'header')
    {
        parent::__construct($name);

        $this->addRowSpecs([
            \App\Crud\Rows\HeroRow::make(),
        ])->setMaxRows(1);
    }
}
