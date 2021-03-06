<?php

namespace TorMorten\Eventy;

abstract class Event
{

    /**
     * Holds the event listeners
     * @var array
     */
    protected $listeners = null;

    public function __construct()
    {
        $this->listeners = collect([]);
    }

    /**
     * Adds a listener
     * @param string  $hook      Hook name
     * @param mixed   $callback  Function to execute
     * @param integer $priority  Priority of the action
     * @param integer $arguments Number of arguments to accept
     */
    public function listen($hook, $callback, $priority = 20, $arguments = 1)
    {
        $this->listeners->push([
            'hook' => $hook,
            'callback' => $callback,
            'priority' => $priority,
            'arguments' => $arguments
        ]);

        return $this;
    }

    /**
     * Gets a sorted list of all listeners
     * @return array
     */
    public function getListeners()
    {
        // $listeners = $this->listeners->values();
        // sort by priority
        // uksort($values, function ($a, $b) {
        //     return strnatcmp($a, $b);
        // });

        return $this->listeners->sortBy('priority');
    }

    /**
     * Gets the function
     * @param  mixed $callback Callback
     * @return mixed           A closure, an array if "class@method" or a string if "function_name"
     */
    protected function getFunction($callback)
    {
        if (is_string($callback) && strpos($callback, '@')) {
            $callback = explode('@', $callback);
            return array(app('\\' . $callback[0]), $callback[1]);
        } elseif (is_callable($callback)) {
            return $callback;
        } else {
            throw new Exception('$callback is not a Callable', 1);
        }
    }

    /**
     * Fires a new action
     * @param  string $action Name of action
     * @param  array  $args   Arguments passed to the action
     */
    abstract public function fire($action, $args);
}
