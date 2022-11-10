<?php

namespace App\Crud\Extras;

use App\Crud\Fields\LocationField;
use Yadda\Enso\Crud\Forms\CollectionSection;
use Yadda\Enso\Crud\Forms\Fields\DividerField;
use Yadda\Enso\Crud\Forms\Fields\TextareaField;
use Yadda\Enso\Crud\Forms\Fields\TextField;
use Yadda\Enso\Crud\Forms\Form;
use Yadda\Enso\Settings\Contracts\ExtraSettings;

class ContactDetails implements ExtraSettings
{
    /**
     * Take a form and process it by adding, removing
     * or updating sections or fields.
     *
     * @param Form $form
     *
     * @return Form
     */
    public static function updateForm(Form $form): Form
    {
        if ($form->hasSection('contact')) {
            $section = $form->getSection('contact');
        } else {
            $section = CollectionSection::make('contact')
                ->setLabel('Contact');

            if ($form->hasSection('main')) {
                $form->addSectionAfter('main', $section);
            } else {
                $form->addSection($section);
            }
        }

        $section->addFields([
            DividerField::make('location')
                ->setTitle('Location'),
            TextField::make('google_place_name')
                ->addFieldsetClass('is-6'),
            TextField::make('google_place_id')
                ->addFieldsetClass('is-6'),
            TextField::make('address_email')
                ->addFieldsetClass('is-6'),
            TextField::make('address_phone')
                ->addFieldsetClass('is-6'),
            TextareaField::make('address')
                ->addFieldsetClass('is-6'),
            LocationField::make('map_location')
                ->setShowCoordFields(true)
                ->setLocationColumn('map_location')
                ->addFieldsetClass('is-6'),
        ]);

        return $form;
    }

}
