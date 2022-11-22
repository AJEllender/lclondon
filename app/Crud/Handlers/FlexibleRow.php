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
     *
     * @var mixed|null
     */
    protected $instance;

    /**
     * The next row
     *
     * @var FlexibleRow|null
     */
    public $next_row;

    /**
     * The previous row
     *
     * @var FlexibleRow|null
     */
    public $previous_row;

    /**
     * Instance of the RowSpec from which this was generated.
     *
     * @var \Yadda\Enso\Crud\Forms\FlexibleContentSection|null
     */
    protected $rowspec_instance;

    /**
     * Unpacked data from this row.
     *
     * @var mixed
     */
    protected $unpacked;

    /**
     * Create a new FlexibleRow
     *
     * @param array   $row_data   Row data from database
     * @param string  $base_class A class to use as the basis for BEM classes
     * @param integer $index      The 0-based index of this row
     */
    public function __construct($row_data, $base_class = 'flexible-content')
    {
        parent::__construct($row_data, $base_class);

        if (array_key_exists($this->getType(), Config::get('enso.flexible-content.options.rows'))) {
            $rowspec_class = Config::get('enso.flexible-content.options.rows')[$this->getType()];
            $this->rowspec_instance = new $rowspec_class;
        }
    }

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
     * Get the initiating rowspec instance of this row.
     *
     * @return mixed
     */
    public function getRowspecInstance()
    {
        return $this->rowspec_instance;
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
     * Determines whether the next row implements a specific feature.
     *
     * @param string $feature
     *
     * @return boolean
     */
    public function nextRowHas(string $feature, ...$args): bool
    {
        if (!$this->next_row || !$this->next_row->getRowspecInstance()) {
            return false;
        }

        switch ($feature) {
            case 'diminished-margins':
                return $this->next_row->getRowspecInstance() instanceof \App\Crud\Contracts\AppliesDiminishedMargins;
                break;
            default:
                return false;
        }
    }

    /**
     * Determines whether the previous row implements a specific feature.
     *
     * @param string $feature First param must always be feature name.
     *
     * @return boolean
     */
    public function previousRowHas(string $feature, ...$args): bool
    {
        if (!$this->previous_row || !$this->previous_row->getRowspecInstance()) {
            return false;
        }

        switch ($feature) {
            case 'diminished-margins':
                return $this->previous_row->getRowspecInstance() instanceof \App\Crud\Contracts\AppliesDiminishedMargins;
                break;
            default:
                return false;
        }
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
     * Parent Rowspec Instance helper function
     *
     * @param mixed|null $rowspec_instance
     *
     * @return mixed|self
     */
    public function rowspecInstance($rowspec_instance = null)
    {
        if (!is_null($rowspec_instance)) {
            return $this->setRowspecInstance($rowspec_instance);
        }

        return $this->getRowspecInstance();
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
     * Set the initiating rowspec instance on this row.
     *
     * @param mixed $rowspec_instance
     *
     * @return self
     */
    public function setRowspecInstance($rowspec_instance = null): self
    {
        $this->rowspec_instance = $rowspec_instance;

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
