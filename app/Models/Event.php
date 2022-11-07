<?php

namespace App\Models;

use App\Traits\HasFlexibleFields;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Yadda\Enso\Crud\Contracts\IsCrudModel as ContractsIsCrudModel;
use Yadda\Enso\Crud\Contracts\Model\IsPublishable as ModelIsPublishable;
use Yadda\Enso\Crud\Traits\HasUuids;
use Yadda\Enso\Crud\Traits\IsCrudModel;
use Yadda\Enso\Crud\Traits\Model\IsPublishable;
use Yadda\Enso\Facades\EnsoCrud;
use Yadda\Enso\Media\Contracts\ImageFile;
use Yadda\Enso\Meta\Traits\HasMeta;
use Yadda\Enso\Users\Contracts\User;

class Event extends Model implements ContractsIsCrudModel, ModelIsPublishable
{
    use HasFactory,
        HasFlexibleFields,
        HasMeta,
        HasUuids,
        IsCrudModel,
        IsPublishable;

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'content' => '[]',
        'data' => '[]',
        'header' => '[]',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'content' => 'array',
        'data' => 'array',
        'end_at' => 'datetime',
        'event_type_id' => 'integer',
        'header' => 'array',
        'image_id' => 'integer',
        'publish_at' => 'datetime',
        'published' => 'boolean',
        'start_at' => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content',
        'data',
        'end_at',
        'event_type_id',
        'excerpt',
        'header',
        'image_id',
        'name',
        'publish_at',
        'published',
        'start_at',
        'ticket_url',
        'uuid',
    ];

    /**
     * Name of the UUID column
     *
     * @var string
     */
    protected $uuid_column = 'uuid';

    /**
     * Type of Event
     *
     * @return BelongsTo
     */
    public function eventType(): BelongsTo
    {
        return $this->belongsTo(EnsoCrud::modelClass('eventtype'));
    }

    /**
     * Gets the image of this Event, either from the override or from the
     * Event Type.
     *
     * @return ImageFile
     */
    public function getEventImage(): ?ImageFile
    {
        return $this->image
            ? $this->image
            : ($this->eventType
                ? $this->eventType->image
                : null);
    }

    /**
     * Gets the name of this Event, either from the override or from the
     * Event Type.
     *
     * @return string
     */
    public function getEventName(): string
    {
        return $this->name
            ? $this->name
            : ($this->eventType
                ? $this->eventType->name
                : '');
    }

    /**
     * Gets the full event name (both the event type and event name, if different)
     *
     * @return string
     */
    public function getFullEventName(): string
    {
        if ($this->eventType && !empty($this->name) && ($this->name !== $this->eventType->name)) {
            return $this->eventType->name . ': ' . $this->name;
        } else {
            return $this->getEventName();
        }
    }

    /**
     * Gets the name of the Publish At DateTime column on this publishable
     *
     * @return string|null
     */
    public function getPublishAtColumn()
    {
        return 'publish_at';
    }

    /**
     * Name of the permission that allows users to view a page irrespective of
     * it's publishing state.
     *
     * @return string|null
     */
    public function getPublishViewOverridePermission()
    {
        return 'view-unpublished-events';
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * The url for this event
     *
     * @return string
     */
    public function getUrl(): string
    {
        if (!$this->eventType) {
            return '';
        }

        return route('events.show', [$this->eventType->slug, $this->uuid]);
    }

    /**
     * Override image for this specific Event.
     *
     * @return BelongsTo
     */
    public function image(): BelongsTo
    {
        return $this->belongsTo(App::make(ImageFile::class), 'image_id');
    }

    /**
     * Filters an Event query to return only those that are upcoming.
     *
     * @param Builder $query
     *
     * @return void
     */
    public function scopeUpcoming(Builder $query): void
    {
        $query->where('start_at', '>=', Carbon::now());
    }
}
