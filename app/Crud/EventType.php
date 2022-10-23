<?php

namespace App\Crud;

use App\Crud\Fields\HeaderField;
use App\Crud\Traits\HasDefaultRowSpecs;
use Illuminate\Validation\Rule;
use Yadda\Enso\Crud\Config;
use Yadda\Enso\Crud\Contracts\Config\IsPublishable as ConfigIsPublishable;
use Yadda\Enso\Crud\Forms\Fields\DateTimeField;
use Yadda\Enso\Crud\Forms\Fields\FileUploadFieldResumable;
use Yadda\Enso\Crud\Forms\Fields\FlexibleContentField;
use Yadda\Enso\Crud\Forms\Fields\SlugField;
use Yadda\Enso\Crud\Forms\Fields\TextField;
use Yadda\Enso\Crud\Forms\Form;
use Yadda\Enso\Crud\Forms\Section;
use Yadda\Enso\Crud\Tables\Text;
use Yadda\Enso\Crud\Tables\Thumbnail;
use Yadda\Enso\Crud\Traits\Config\IsPublishable;
use Yadda\Enso\Facades\EnsoCrud;
use Yadda\Enso\Meta\Crud\MetaSection;

class EventType extends Config implements ConfigIsPublishable
{
    use HasDefaultRowSpecs,
        IsPublishable;

    public function configure()
    {
        $model_class = EnsoCrud::modelClass('eventtype');
        $model_instance = new $model_class;

        $this->model($model_class)
            ->route('admin.event-types')
            ->views('event-types')
            ->name('Event Type')
            ->columns([
                Thumbnail::make('image')
                    ->setLabel('')
                    ->orderableBy(null)
                    ->addThClass('is-narrow')
                    ->setFormatter(function ($x, $item) {
                        return $item->image
                            ? $item->image->getPreview()
                            : null;
                    }),
                Text::make('name'),
                $this->isPublishablePublishTableCell(),

            ])
            ->rules([
                'main.name' => ['required', 'string', 'min:3'],
                'main.slug' => ['nullable', Rule::unique($model_instance->getTable(), $model_instance->getKeyName())],
            ]);
    }

    public function create(Form $form)
    {
        $form->addSections([
            Section::make('main')
                ->addFields([
                    FileUploadFieldResumable::make('image')
                        ->addFieldsetClass('is-half'),
                    TextField::make('name')
                        ->addFieldsetClass('is-half'),
                    DateTimeField::make('publish_at')
                        ->addFieldsetClass('is-half'),
                    SlugField::make('slug')
                        ->addFieldsetClass('is-half')
                        ->setRoute(route('event-types.show', '%SLUG%')),
                ]),
            Section::make('content')
                ->addFields([
                    TextField::make('excerpt'),
                    HeaderField::make('header'),
                    FlexibleContentField::make('content')
                        ->addRowSpecs($this->defaultRowSpecs()),
                ]),
            MetaSection::make('meta'),
        ]);

        return $form;
    }
}
