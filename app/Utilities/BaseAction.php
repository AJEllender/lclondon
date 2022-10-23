<?php

namespace App\Utilities;

/**
 * Boilerplate for a synchronously actionable utility class.
 */
abstract class BaseAction
{
    /**
     * Instantiate the action
     *
     * @return static
     */
    public static function make()
    {
        return new static();
    }

    /**
     * Run the Action
     *
     * @param mixed[] ...$args
     *
     * @return mixed
     */
    public static function run(...$args)
    {
        return static::make()(...$args);
    }
}
