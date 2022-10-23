<?php

namespace App\Utilities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Yadda\Enso\Facades\EnsoCrud;

class Is
{
    /**
     * Determines whether the given value is a Collection instance.
     *
     * @param mixed $value
     *
     * @return boolean
     */
    public static function collection($value): bool
    {
        return is_object($value) && ($value instanceof Collection);
    }

    /**
     * Determines whether the given value is a Model instance.
     *
     * @param mixed $value
     *
     * @return boolean
     */
    public static function model($value): bool
    {
        return is_object($value) && ($value instanceof Model);
    }

    /**
     * Determines whether the given value is a User instance.
     *
     * @param mixed $value
     *
     * @return boolean
     */
    public static function user($value): bool
    {
        $user_class = EnsoCrud::modelClass('user');
        return is_object($value) && ($value instanceof $user_class);
    }
}
