<?php

namespace App\Actions;

use App\Utilities\BaseAction;
use App\Utilities\Is;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Yadda\Enso\Crud\Contracts\IsCrudModel;

class MapForCrud extends BaseAction
{
    /**
     * @param Collection|Model
     *
     * @return bool
     */
    public function __invoke($argument)
    {
        if (Is::collection($argument)) {
            return $argument->map(function ($model) {
                return $this->modelToCrudMapping($model);
            });
        } elseif (Is::model($argument)) {
            return $this->modelToCrudMapping($argument);
        } else {
            return $argument;
        }
    }

    /**
     * Maps a CrudModel for use as a Crud Option
     *
     * @param Model $model
     *
     * @return array
     */
    protected function modelToCrudMapping(Model $model): array
    {
        if (!$model instanceof IsCrudModel) {
            throw new Exception('Model must implement IsCrudModel');
        }

        return [
            Config::get('enso.settings.select_track_by') => $model->getKey(),
            Config::get('enso.settings.select_label_by') => $model->getCrudLabel(),
        ];
    }
}
