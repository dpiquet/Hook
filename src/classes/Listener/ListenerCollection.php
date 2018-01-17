<?php

namespace Dpiquet\Hook\Listener;


class ListenerCollection implements \Iterator, \Countable
{

    /**
     * @var Listener[]
     */
    private $listeners = [];

    /**
     * @var int
     */
    private $cursor = 0;

    public function __construct()
    {
    }

    /**
     * @return Listener[]
     */
    public function getListeners()
    {
        return $this->listeners;
    }


    /**
     * Add a listener to the collection
     * @param Listener $listener
     * @return ListenerCollection
     */
    public function addListener(Listener $listener)
    {
        $this->listeners[] = $listener;

        // Sort listeners by priority
        usort($this->listeners, function (Listener $a, Listener $b) {
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
     * @return Listener
     */
    public function current()
    {
        return $this->listeners[$this->cursor];
    }

    public function next()
    {
        $this->cursor++;
    }

    public function key()
    {
        return $this->cursor;
    }

    public function valid()
    {
        return (count($this->listeners) > $this->cursor) ? true : false;
    }

    public function rewind()
    {
        $this->cursor = 0;
    }

    public function count()
    {
        return count($this->listeners);
    }

}