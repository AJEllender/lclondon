<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\Model;
use Yadda\Enso\Crud\Controller;
use Yadda\Enso\Crud\Traits\Controller\IsPublishable;

class EventController extends Controller
{
    use IsPublishable;

    /**
     * Clone an item
     *
     * @param Model $item
     *
     * @return Model
     */
    protected function cloneItem(Model $item): Model
    {
        return $item->replicate(['uuid', 'published']);
    }
}
