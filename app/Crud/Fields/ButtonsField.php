<?php

namespace App\Crud\Fields;

use App\Crud\Rows\ButtonRow;
use Yadda\Enso\Crud\Forms\Fields\FlexibleContentField;
use Yadda\Enso\Crud\Traits\FieldHasRowSpecs;

class ButtonsField extends FlexibleContentField
{
    use FieldHasRowSpecs;

    public function __construct(string $name = 'main')
    {
        parent::__construct($name);

        $this->addRowSpecs(
            $this->getButtonDefinitions()
        )->addRowLabel('Add button');
    }

    /**
     * Allows only a single button and prevents duplication (which can be used
     * to get around max row restrictions).
     *
     * @return ButtonsField
     */
    public function singleButton(): ButtonsField
    {
        $this->setMaxRows(1);
        $this->getRowSpecs()->each->allowDuplication(false);

        return $this;
    }

    /**
     * Definition of any buttons this row should implement.
     *
     * @return array
     */
    protected function getButtonDefinitions(): array
    {
        return [
            ButtonRow::make('buttonrow')
                ->setLabel('Button')
                ->withoutCommon(),
        ];
    }
}
