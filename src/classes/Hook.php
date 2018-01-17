<?php


namespace Dpiquet\Hook;

use Dpiquet\Hook\Listener\Listener;
use Dpiquet\Hook\Listener\ListenerCollection;
use Dpiquet\Hook\Modifier\Modifier;
use Dpiquet\Hook\Modifier\ModifierCollection;

/**
 * Class Hook
 *
 */
class Hook
{

    /**
     * @var ListenerCollection[]
     */
    static private $listenerCollections;

    /**
     * @var array
     */
    static private $modifierCollections;

    /**
     * @var Hook
     */
    static private $instance;

    /**
     * Private (singleton)
     * Hook constructor.
     */
    private function __construct()
    {
        self::$listenerCollections = [];
        self::$modifierCollections = [];
    }


    /**
     * Get singleton instance
     *
     * @return Hook
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * Register a callback on event
     *
     * @param string $event
     * @param callable $callback
     * @param int $priority
     */
    public static function registerListener($event, callable $callback, $priority = 500)
    {
        self::getInstance();

        $listener = new Listener($event, $callback, $priority);

        if (!array_key_exists($event, self::$listenerCollections)) {
            self::$listenerCollections[$event] = new ListenerCollection();
        }

        self::$listenerCollections[$event]->addListener($listener);
    }


    /***
     * Register a modifier callback
     *
     * @param $event
     * @param callable $callback
     * @param int $priority
     */
    public static function registerModifier($event, callable $callback, $priority = 500)
    {
        self::getInstance();

        $modifier = new Modifier($event, $callback, $priority);

        if (!array_key_exists($event, self::$modifierCollections)) {
            self::$modifierCollections[$event] = new ModifierCollection();
        }

        self::$modifierCollections[$event]->addModifier($modifier);
    }


    /**
     * Call registered listeners
     *
     * @param $event
     * @param mixed $args,... Variable number of arguments
     */
    public static function callListeners($event, &...$args)
    {
        self::getInstance();

        if (!array_key_exists($event, self::$listenerCollections)) {
            return;
        }

        foreach(self::$listenerCollections[$event] as $listener) {
            call_user_func_array([$listener, 'call'], $args);
        }
    }


    /**
     * Call modifiers
     *
     * @param string $event
     * @param mixed $value
     * @return mixed
     */
    public static function callModifiers($event, $value = '')
    {
        self::getInstance();
        
        if (!array_key_exists($event, self::$modifierCollections)) {
            return $value;
        }

        foreach(self::$modifierCollections[$event] as $modifier) {
            $value = $modifier->call($value);
        }
        
        return $value;
    }

}