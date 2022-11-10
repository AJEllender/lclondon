<?php

namespace App\Utilities;

use Illuminate\Support\Arr;
use Yadda\Enso\Settings\Facades\EnsoSettings;

class Location
{
    public function getAddress(): string
    {
        return EnsoSettings::get('address') ?? '';
    }

    /**
     * The Google 'Get Directions' url.
     *
     * @return string
     */
    public function getDirectionsUrl(): string
    {
        $place_id = EnsoSettings::get('google_place_id');
        $place_name = EnsoSettings::get('google_place_name');

        if (empty($place_id) || empty($place_name)) {
            return '';
        }

        return implode('&', array_filter([
            'https://www.google.com/maps/dir/?api=1',
            http_build_query(array_filter([
                'destination_place_id' => $place_id,
                'destination' => $place_name,
                ]))
            ]));
    }

    public function getEmail(): string
    {
        if (!empty($email = (EnsoSettings::get('address_email') ?? ''))) {
            return $email;
        }

        return EnsoSettings::get('administrator-email') ?? '';
    }

    public function getLatitude(): string
    {
        return Arr::get(EnsoSettings::get('map_location'), 'location.lat', '');
    }

    public function getLongitude(): string
    {
        return  Arr::get(EnsoSettings::get('map_location'), 'location.lng', '');
    }

    public function getPhone(): string
    {
        return EnsoSettings::get('address_phone') ?? '';
    }
}
