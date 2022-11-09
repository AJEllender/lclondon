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
     * A CSS class to use as the basis for BEM classes
     *
     * @var string
     */
    protected $base_class;

    /**
     * CSS classes to apply to the row
     *
     * @var array
     */
    protected $classes;

    /**
     * The string identifier of this row type. E.g. 'image_text' or 'wysiwyg'
     *
     * This value will be used to associate data in the database with row specs
     *
     * @var string
     */
    protected $type;

    /**
     * The blocks (i.e. fields) on this row
     *
     * @var Collection
     */
    protected $blocks;

    /**
     * The settings blcoks (i.e.settings fields) on this row
     *
     * @var Collection
     */
    protected $settings_blocks;

    /**
     * The 0-based index of this row
     *
     * @var int
     */
    protected $index;

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
     * Create a new FlexibleRow
     *
     * @param array   $row_data   Row data from database
     * @param string  $base_class A class to use as the basis for BEM classes
     * @param integer $index      The 0-based index of this row
     */
    public function __construct($row_data, $base_class = 'flexible-content')
    {
        $this
            ->setBaseClass($base_class)
            ->setType($row_data['type'])
            ->setDefaultClass(!empty($row_data['type']) ? $row_data['type'] : null)
            ->setBlocks($row_data['fields'])
            ->setSettingsBlocks($row_data['settings_fields'] ?? []);

        if (isset($row_data['class'])) {
            $this->addClasses(explode(' ', $row_data['class']), true);
        }
    }

    /**
     * Adds a single class to the row, if it isn't already present
     *
     * @param string  $class  Class to add
     * @param boolean $as_bem Whether to BEM the class
     *
     * @return self
     */
    public function addClass(string $class, $as_bem = false)
    {
        if ($as_bem) {
            $class = $this->makeBemModifierClass($class);
        }

        if (!in_array($class, $this->classes)) {
            $this->classes[] = $class;
        }

        return $this;
    }

    /**
     * Adds an array of classes to the row, if they don't already exist
     *
     * @param array   $classes Classes to add
     * @param boolean $as_bem  Whether to BEM the class
     *
     * @return self
     */
    public function addClasses(array $classes, $as_bem = false)
    {
        if ($as_bem) {
            foreach ($classes as $index => $class) {
                $classes[$index] = $this->makeBemModifierClass($class);
            }
        }

        $this->classes = array_unique(array_merge($this->classes, $classes));

        return $this;
    }

    /**
     * Get a single block by name
     *
     * @param string $name
     *
     * @return FlexibleBlock|null
     */
    public function block($name): ?FlexibleBlock
    {
        return $this->blocks->get($name);
    }

    /**
     * Gets the content of the block with the given name.
     *
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function blockContent($name, $default = null)
    {
        if ($this->block($name)) {
            return $this->block($name)->getContent();
        }

        return $default;
    }

    /**
     * Get the base CSS class
     *
     * @return string
     */
    public function getBaseClass()
    {
        return $this->base_class;
    }

    /**
     * Gets the collection of blocks
     *
     * @return Collection
     */
    public function getBlocks(): Collection
    {
        return $this->blocks;
    }

    /**
     * Gets the current classes, by either as a string (by default) or as an
     * array
     *
     * @param boolean $as_array Whether to return as array
     *
     * @return mixed Formatted class list
     */
    public function getClasses($as_array = false)
    {
        if ($as_array) {
            return $this->classes;
        }

        return implode(' ', $this->classes);
    }

    /**
     * Gets the collection of settings blocks
     *
     * @return Collection
     */
    public function getSettingsBlocks(): Collection
    {
        return $this->settings_blocks;
    }

    /**
     * Get the string identifier for this row type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Prefixes the given class with the BEM base class for this block
     *
     * @param string $class Original class
     *
     * @return string BEM class
     */
    protected function makeBemModifierClass($class)
    {
        return $this->getBaseClass() . '__row--' . $class;
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
    }

    /**
     * Removes an array of classes from the current list, if they are present
     *
     * @param array $classes Classes to remove
     *
     * @return self
     */
    public function removeClasses(array $classes)
    {
        $this->array_classes = array_diff($this->classes, $classes);

        return $this;
    }

    /**
     * Set the base CSS class for use in BEM classes
     *
     * @param string $class
     *
     * @return self
     */
    protected function setBaseClass($class)
    {
        $this->base_class = $class;

        return $this;
    }

    /**
     * Sets the collection of blocks in this row. Converts an array to a
     * collection, and blocks given as an array to FlexibleBlock type
     *
     * @param  array $blocks Blocks to set
     *
     * @return self
     */
    public function setBlocks($blocks)
    {
        if (is_array($blocks)) {
            if (count($blocks)) {
                if (!Arr::first($blocks) instanceof FlexibleBlock) {
                    foreach ($blocks as $index => $block) {
                        $blocks[$index] = new FlexibleBlock($block, $index, $this->getBaseClass());
                    }
                }
            }

            $blocks = new Collection($blocks);
        }

        $this->blocks = $blocks;

        return $this;
    }

    /**
     * Sets the current row classes array
     *
     * @param array $classes Classes to set
     */
    public function setClasses(array $classes)
    {
        $this->classes = $classes;

        return $this;
    }

    /**
     * Sets the default class array based on the 'base_class' property and the
     * types of block present
     *
     * @param string $type Identifier for this row
     *
     * @return self
     */
    protected function setDefaultClass($type = null)
    {
        $classes = [$this->getBaseClass() . '__row'];

        if (is_null($type)) {
            if ($this->getBlocks()->count()) {
                $content_types = $this->getBlocks()->map(function ($item) {
                    return $item->getType();
                })->toArray();

                $classes[] = $this->makeBemModifierClass(implode('-', $content_types));
            }
        } else {
            $classes[] = $this->makeBemModifierClass($type);
        }

        $this->setClasses($classes);

        return $this;
    }

    /**
     * Sets the collection of settings blocks in this row. Converts an array
     * to a collection, and blocks given as an array to FlexibleBlock type
     *
     * @param array $blocks Blocks to set
     *
     * @return self
     */
    public function setSettingsBlocks($blocks)
    {
        if (is_array($blocks)) {
            if (count($blocks)) {
                if (!Arr::first($blocks) instanceof FlexibleBlock) {
                    foreach ($blocks as $index => $block) {
                        $blocks[$index] = new FlexibleBlock($block, $index, $this->getBaseClass());
                    }
                }
            }

            $blocks = new Collection($blocks);
        }

        $this->settings_blocks = $blocks;

        return $this;
    }

    /**
     * Get a single setting by name
     *
     * @param string $name
     *
     * @return FlexibleBlock|null
     */
    public function setting($name): ?FlexibleBlock
    {
        return $this->settings_blocks->get($name);
    }

    /**
     * Gets the content of the block with the given name.
     *
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function settingContent($name, $default = null)
    {
        if ($this->setting($name)) {
            return $this->setting($name)->getContent();
        }

        return $default;
    }

    /**
     * Set the string identifier for this row type
     *
     * @param string $type
     *
     * @return self
     */
    protected function setType($type)
    {
        $this->type = $type;

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
