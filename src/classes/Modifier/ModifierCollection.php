<?php

namespace Dpiquet\Hook\Modifier;

class ModifierCollection implements \Iterator, \Countable
{

    /**
     * @var Modifier[]
     */
    private $modifiers = [];

    /**
     * @var int
     */
    private $cursor = 0;

    public function __construct()
    {
    }

    /**
     * @return Modifier[]
     */
    public function getModifiers()
    {
        return $this->modifiers;
    }


    /**
     * Add a modifier to the collection
     * @param Modifier $modifier
     * @return ModifierCollection
     */
    public function addModifier(Modifier $modifier)
    {
        $this->modifiers[] = $modifier;

        // Sort listeners by priority
        usort($this->modifiers, function (Modifier $a, Modifier $b) {
            if ($a->getPriority() == $b->getPriority()) {
                return 0;
            }

            if ($a->getPriority() > $b->getPriority()) {
                return 1;
            }

            return -1;
        });

        return $this;
    }

    /**
     * @return Modifier
     */
    public function current()
    {
        return $this->modifiers[$this->cursor];
    }

    public function next()
    {
        $this->cursor++;
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->cursor;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return (count($this->modifiers) > $this->cursor) ? true : false;
    }

    public function rewind()
    {
        $this->cursor = 0;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->modifiers);
    }

}