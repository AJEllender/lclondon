<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\Builder;
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

    /**
     * Apply a search term to a query
     *
     * @param Builder $query       Initial Query
     * @param string  $search_term String to search with
     *
     * @return Builder
     */
    protected function doSearch(Builder $query, $search_term)
    {
        $search_cols = $this->getConfig()->getSearchColumns();

        if ($this->config->getSearchJoinsCallback()) {
            $query = ($this->config->getSearchJoinsCallback())($query);
        }

        $query->where(
            function ($query) use ($search_cols, $search_term) {
                foreach ($search_cols as $col) {
                    $query->orWhere($col, 'LIKE', '%' . $search_term . '%');
                }

                $query->orWhereHas('eventType', function ($query) use ($search_term) {
                    $query->where('name', 'LIKE', '%' . $search_term . '%');
                });
            }
        );

        return $query;
    }
}
