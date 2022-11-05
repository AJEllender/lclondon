<?php

namespace App\Crud;

use Yadda\Enso\Crud\Forms\Fields\SelectField;
use Yadda\Enso\Crud\Forms\Fields\TextField;
use Yadda\Enso\Crud\Forms\FlexibleContentSection;
use Yadda\Enso\SiteMenus\Crud\Menu;

class MenuCrud extends Menu
{
    /**
     * Creates a MenuItem rowspec
     *
     * @return FlexibleContentSection
     */
    protected function createMenuItemRowspec(): FlexibleContentSection
    {
        return FlexibleContentSection::make('items')
            ->setLabel('Menu Item')
            ->excerptField('label')
            ->addFields([
                TextField::make('id')
                    ->addFieldSetClass('is-hidden')
                    ->setDisabled(),
                TextField::make('label')
                    ->addFieldSetClass('is-3'),
                TextField::make('url')
                    ->addFieldSetClass('is-6'),
                SelectField::make('target')
                    ->addFieldSetClass('is-3')
                    ->setLabel('Open in')
                    ->setOptions([
                        '_blank' => 'New Tab',
                        '_self' => 'Current Tab',
                    ]),
            ]);
    }
}
