<?php

namespace App\Utilities;

class BackgroundTexture
{
    public static function from(string $background_texture): string
    {
        switch ($background_texture) {
            case 'black':
                return 'bg-black';
            case 'dark':
                return 'bg-gray-900';
            case 'white':
                return 'bg-white';
            case 'light':
            default:
                return 'bg-grey-100';
        }
    }
}
