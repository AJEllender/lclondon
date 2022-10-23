<?php

namespace App\Crud;

use App\Crud\Fields\HeaderField;
use App\Crud\Traits\HasDefaultRowSpecs;
use Yadda\Enso\Crud\Forms\Form;
use Yadda\Enso\Pages\Crud\Page as BaseCrud;

class Page extends BaseCrud
{
    use HasDefaultRowSpecs;

    /**
     * Default form configuration.
     *
     * @return \Yadda\Enso\Crud\Forms\Form
     */
    public function create(Form $form)
    {
        $form = parent::create($form);

        $form->getSection('content')
            ->addFieldAfter(
                'excerpt',
                HeaderField::make('header')
            );

        return $form;
    }
}
