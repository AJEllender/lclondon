<?php

namespace App\Models;

use App\Traits\HasFlexibleFields;
use Yadda\Enso\Pages\Models\Page as BaseModel;

class Page extends BaseModel
{
    use HasFlexibleFields;

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'content' => '[]',
        'data' => '[]',
        'header' => '[]',
        'template' => 'default',
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
        'publish_at' => 'datetime',
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
        'order',
        'parent_id',
        'published',
        'publish_at',
        'slug',
        'template',
        'thumbnail_id',
        'title',
    ];
}
