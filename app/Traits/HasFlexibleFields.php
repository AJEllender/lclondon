<?php

namespace App\Traits;

use Yadda\Enso\Crud\Traits\HasFlexibleFields as BaseTrait;

trait HasFlexibleFields
{
    use BaseTrait;

    /**
     * Whether this page has header content
     *
     * @param string $attribute
     *
     * @return bool
     */
    public function hasFlexibleContent(string $attribute = 'content'): bool
    {
        return count($this->{$attribute} ?? []);
    }
}
