<?php

namespace App\Models;

use App\Traits\HasFlexibleFields;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Yadda\Enso\Crud\Contracts\IsCrudModel as ContractsIsCrudModel;
use Yadda\Enso\Crud\Contracts\Model\IsPublishable as ModelIsPublishable;
use Yadda\Enso\Crud\Traits\IsCrudModel;
use Yadda\Enso\Crud\Traits\Model\IsPublishable;
use Yadda\Enso\Facades\EnsoCrud;
use Yadda\Enso\Media\Contracts\ImageFile;
use Yadda\Enso\Meta\Traits\HasMeta;
use Yadda\Enso\Users\Contracts\User;

class EventType extends Model implements ContractsIsCrudModel, ModelIsPublishable
{
    use HasFactory,
        HasFlexibleFields,
        HasMeta,
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
        'header' => 'array',
        'image_id' => 'integer',
        'publish_at' => 'datetime',
        'published' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content',
        'data',
        'excerpt',
        'header',
        'image_id',
        'name',
        'publish_at',
        'published',
        'slug',
    ];

    /**
     * Events of this EventType
     *
     * @return HasMany
     */
    public function events(): HasMany
    {
        return $this->hasMany(EnsoCrud::modelClass('event'));
    }

    /**
     * Name of this EventType
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name ?? '';
    }

    /**
     * Gets the name of the publish at datetime column on this publishable
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
        return 'view-unpublished-event-types';
    }

    /**
     * Get the route key for the EventType.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Image for this EventType.
     *
     * @return BelongsTo
     */
    public function image(): BelongsTo
    {
        return $this->belongsTo(App::make(ImageFile::class), 'image_id');
    }

    /**
     * Orders an Adventure Query by the date of it's next available departure
     *
     * @param Builder $query
     *
     * @return void
     */
    public function scopeOrderByEventDates(Builder $query, string $order = 'ASC'): void
    {
        $event_type_class = EnsoCrud::modelClass('eventtype');
        $event_type_key_name = (new $event_type_class)->getQualifiedKeyName();

        $select_query = EnsoCrud::query('event')
                ->accessibleToUser()
                ->upcoming()
                ->selectRaw('MIN(start_at)')
                ->whereColumn('event_type_id', $event_type_key_name)
                ->take(1);

        $query->addSelect([
            'next_start_date' => $select_query
        ])->orderBy('next_start_date', $order);
    }

    /**
     * Limit an EventType query to only those that have upcoming events.
     *
     * @param Builder $query
     *
     * @return void
     */
    public function scopeWithFutureEvents(Builder $query): void
    {
        $query->whereHas('events', function ($query) {
            $query->upcoming();
        });
    }
}
