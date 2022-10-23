<?php

namespace App\Crud;

use App\Actions\MapForCrud;
use App\Crud\Fields\HeaderField;
use App\Crud\Traits\HasDefaultRowSpecs;
use Illuminate\Validation\Rule;
use Yadda\Enso\Crud\Config;
use Yadda\Enso\Crud\Contracts\Config\IsPublishable as ConfigIsPublishable;
use Yadda\Enso\Crud\Forms\Fields\BelongsToField;
use Yadda\Enso\Crud\Forms\Fields\DateTimeField;
use Yadda\Enso\Crud\Forms\Fields\DividerField;
use Yadda\Enso\Crud\Forms\Fields\FileUploadFieldResumable;
use Yadda\Enso\Crud\Forms\Fields\FlexibleContentField;
use Yadda\Enso\Crud\Forms\Fields\StaticTextField;
use Yadda\Enso\Crud\Forms\Fields\TextField;
use Yadda\Enso\Crud\Forms\Form;
use Yadda\Enso\Crud\Forms\Section;
use Yadda\Enso\Crud\Tables\Text;
use Yadda\Enso\Crud\Tables\Thumbnail;
use Yadda\Enso\Crud\Traits\Config\IsPublishable;
use Yadda\Enso\Facades\EnsoCrud;
use Yadda\Enso\Meta\Crud\MetaSection;

class Event extends Config implements ConfigIsPublishable
{
    use HasDefaultRowSpecs,
        IsPublishable;

    public function configure()
    {
        $event_class = EnsoCrud::modelClass('event');
        $event_instance = new $event_class;

        $event_type_class = EnsoCrud::modelClass('eventtype');
        $event_type_instance = new $event_type_class;

        $this->model($event_class)
            ->route('admin.events')
            ->views('events')
            ->name('Event')
            ->columns([
                Thumbnail::make('image')
                    ->setLabel('')
                    ->orderableBy(null)
                    ->addThClass('is-narrow')
                    ->setFormatter(function ($x, $item) {
                        $image = $item->getEventImage();
                        return $image
                            ? $image->getPreview()
                            : null;
                    }),
                Text::make('name')
                    ->orderableBy(null)
                    ->setFormatter(function ($x, $item) {
                        return $item->getEventName();
                    }),
                Text::make('start_at')
                    ->setFormatter(function ($start_date) {
                        return $start_date
                            ? $start_date->format('H:i, jS M Y')
                            : 'Not Set';
                    }),
                $this->isPublishablePublishTableCell(),
            ])
            ->rules([
                'main.name' => ['nullable', 'string'],
                'main.uuid' => ['nullable', Rule::unique($event_instance->getTable(), 'uuid')],
                'main.eventType.id' => ['required', Rule::exists($event_type_instance->getTable(), $event_type_instance->getKeyName())],
                'main.start_at.date' => ['nullable', 'date'],
                'main.end_at.date' => ['nullable', 'date', 'after_or_equal:main.start_at.date'],
            ])->messages([
                'main.eventType.id.required' => 'An Event Type is required.',
                'main.end_at.date.after_or_equal' => 'End Date must be after Start Date.',
            ]);
    }

    public function create(Form $form)
    {
        $event_types = EnsoCrud::modelClass('eventtype')::get();

        $form->addSections([
            Section::make('main')
                ->addFields([
                    DividerField::make('event_details_divider')
                        ->setTitle('Event Details'),
                    TextField::make('uuid')
                        ->addFieldsetClass('is-half')
                        ->setDisabled(),
                    BelongsToField::make('eventType')
                        ->setLabel('Event Type')
                        ->addFieldsetClass('is-half')
                        ->setOptions(MapForCrud::run($event_types)),
                    DateTimeField::make('start_at')
                        ->addFieldsetClass('is-half'),
                    DateTimeField::make('end_at')
                        ->addFieldsetClass('is-half'),
                    TextField::make('ticket_url')
                        ->setHelpText('Fill this in with a URL to the ticket sales page for this event.'),
                    DividerField::make('event_type_overrides_divider')
                        ->setTitle('Event Type Overrides'),
                    StaticTextField::make('event_type_overrides_description')
                        ->setContent('These fields override the values set in the Event Type. For example, if you have a Paunchy Event Type, but want to run a special Christmas Edition with a different image and name, update them here.'),
                    FileUploadFieldResumable::make('image')
                        ->addFieldsetClass('is-half'),
                    TextField::make('name')
                        ->addFieldsetClass('is-half'),
                    DividerField::make('publish_divider')
                        ->setTitle('Publishing'),
                    DateTimeField::make('publish_at')
                        ->addFieldsetClass('is-half'),
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
