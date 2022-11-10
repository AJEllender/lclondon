<?php

namespace App\Crud\Fields;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Yadda\Enso\Crud\Forms\Fields\LocationField as BaseField;

class LocationField extends BaseField
{
    /**
     * Hook for modifying the form data, if required
     *
     * @param  mixed    $data           Original data
     * @return mixed                    Modified data
     */
    protected function modifyFormData($data)
    {
        if (!$data) {
            $data = $this->getDefaultValue()['location'];
        }

        if (is_array($data)) {
            return Arr::only($data, 'location', []);
        }

        if (is_null($data)) {
            return [
                'location' => null,
            ];
        }

        return [
            'location' => [
                'lng' => $data->getLng(),
                'lat' => $data->getLat(),
            ],
        ];
    }

    // /**
    //  * Convert the field to an array
    //  *
    //  * @return Array
    //  */
    // public function toArray()
    // {
    //     $array = parent::toArray();
    //     $array['props']['use_location']      = $this->use_location;
    //     $array['props']['use_boundary_box']  = $this->use_boundary_box;
    //     $array['props']['use_zoom']          = $this->use_zoom;
    //     $array['props']['use_polygon']       = $this->use_polygon;
    //     $array['props']['use_circle_radius'] = $this->use_circle_radius;
    //     $array['props']['show_coord_fields'] = $this->show_coord_fields;
    //     return $array;
    // }
}
