<?php

namespace App\Crud\Handlers;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Yadda\Enso\Crud\Handlers\FlexibleBlock;
use Yadda\Enso\Crud\Handlers\FlexibleRow as BaseClass;

class FlexibleRow extends BaseClass
{
    /**
     * The parent instance for this row.
     */
    protected $instance;

    /**
     * Unpacked data from this row.
     */
    protected $unpacked;

    /**
     * The previous row
     *
     * @var FlexibleRow|null
     */
    public $previous_row;

    /**
     * The next row
     *
     * @var FlexibleRow|null
     */
    public $next_row;

    /**
     * Get the parent instance of this row.
     *
     * @return mixed
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Parent Instance helper function
     *
     * @param mixed|null $instance
     *
     * @return mixed|self
     */
    public function instance($instance = null)
    {
        if (!is_null($instance)) {
            return $this->setInstance($instance);
        }

        return $this->getInstance();
    }

    /**
     * Removes a single class from the current list, if it is present
     *
     * @param string $class Class to remove
     *
     * @return self
     */
    public function removeClass(string $class)
    {
        if (($class_index = array_search($class, $this->array_classes)) !== false) {
            unset($this->array_classes[$class_index]);
        }

        return $this;
    }

    /**
     * Set the parent instance on this row.
     *
     * @param mixed $instance
     *
     * @return self
     */
    public function setInstance($instance = null): self
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * Unpacks data from from this Flexible row.
     *
     * @return object
     */
    public function unpack(): object
    {
        if (is_null($this->unpacked)) {
            if (array_key_exists($this->getType(), Config::get('enso.flexible-content.options.rows'))) {
                $this->unpacked = Config::get('enso.flexible-content.options.rows.' . $this->getType())::unpack($this);
            } else {
                $this->unpacked = (object) [];
            }
        }

        return $this->unpacked;
    }
}
