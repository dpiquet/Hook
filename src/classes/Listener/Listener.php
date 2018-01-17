<?php

namespace Dpiquet\Hook\Listener;


class Listener
{
    /**
     * @var string
     */
    private $event;

    /**
     * @var callable
     */
    private $callback;

    /**
     * @var int
     */
    private $priority;

    /**
     * Listener constructor.
     *
     * @param string $event
     * @param callable $callback
     * @param int $priority
     */
    public function __construct($event, callable $callback, $priority)
    {
        $this->event = $event;
        $this->callback = $callback;
        $this->priority = $priority;
    }

    /**
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @return callable
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Call the callback function
     *
     * @param array ...$args
     */
    public function call(&...$args)
    {
        call_user_func_array($this->getCallback(), $args);
    }
}