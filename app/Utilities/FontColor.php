<?php

namespace App\Utilities;

class FontColor
{
    public static function from(string $font_color): string
    {
        switch ($font_color) {
            case 'black':
                return 'text-black';
            case 'dark':
                return 'text-gray-900';
            case 'white':
                return 'text-white';
            case 'light':
            default:
                return 'text-grey-100';
        }
    }
}
