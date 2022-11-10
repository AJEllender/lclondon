<?php

namespace App\Utilities;

class BackgroundColor
{
    public static function from(string $background_color): string
    {
        switch ($background_color) {
            case 'black':
                return 'bg-black';
                break;
            case 'white':
                return 'bg-white';
                break;
            case 'dark':
                return 'bg-gray-900';
                break;
            case 'light':
                return 'bg-gray-100';
                break;
            default:
                return 'bg-' . $background_color . '-500';
                break;
        }
    }
}
