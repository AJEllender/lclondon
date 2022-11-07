<?php

namespace App\Models;

use App\Traits\HasFlexibleFields;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\App;
use Yadda\Enso\Crud\Contracts\IsCrudModel as ContractsIsCrudModel;
use Yadda\Enso\Crud\Contracts\Model\IsPublishable as ModelIsPublishable;
use Yadda\Enso\Crud\Traits\IsCrudModel;
use Yadda\Enso\Crud\Traits\Model\IsPublishable;
use Yadda\Enso\Facades\EnsoCrud;
use Yadda\Enso\Media\Contracts\ImageFile;
use Yadda\Enso\Meta\Traits\HasMeta;

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

    public function scopeWithFutureEvents(Builder $query): void
    {
        $query->whereHas('events', function ($query) {
            $query->upcoming();
        });
    }
}
