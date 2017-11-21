<?php

namespace Isholao\Events;

/**
 * @author Ishola O <ishola.tolu@outlook.com>
 */
class Dispatcher implements DispatcherInterface
{

    private $events = [];
    private $sorted = [];
    private $calledListeners = [];

    /**
     * Register a listener to an event
     * 
     * @param string $event
     * @param callable|string $callback  
     * @param int $priority         
     * @return void
     */
    public function on(string $event, $callback, int $priority = 0)
    {
        if (empty($event))
        {
            throw new \InvalidArgumentException('Event name cannot be empty.');
        }

        $key = \strtolower($event);
        $this->events[$key][$priority][] = new \CallableResolver\DeferredCallable($callback);
        unset($this->sorted[$key]);
        return $this;
    }

    /**
     * Add a new event listener
     * 
     * @param EventListenerInterface $listener
     * @return $this
     */
    public function addListener(EventListenerInterface $listener)
    {
        foreach ($listener->getEvents() as $eventName => $params)
        {
            if (\is_string($params))
            {
                $this->on($eventName, [$listener, $params]);
            } elseif (\is_array($params))
            {
                foreach ($params as $data)
                {
                    //data contains method name and priority
                    if (\is_callable($caller = [$listener, $data[0] ?? '']))
                    {
                        $this->on($eventName, $caller, (int) ($data[1] ?? 0));
                    }
                }
            }
        }

        return $this;
    }

    /**
     * Notify all registered listeners
     * 
     * @param string $name Event name
     * @param Event $event Arguments to be passed to the event
     * @return Event
     */
    public function notify(string $name, Event $event): Event
    {
        if (empty($name))
        {
            throw new \InvalidArgumentException('Name cannot be empty.');
        }

        $key = \strtolower($name);
        $event->setName($name);
        $listeners = $this->getEventListeners($name);

        foreach ($listeners as $callback)
        {
            \call_user_func($callback, $event);
            $this->calledListeners[$key][] = &$callback;
            if ($event->canCancel() && $event->hasStopped())
            {
                break;
            }
        }

        return $event;
    }

    /**
     * Has listeners
     * @param string $event
     * @return bool
     */
    function hasListeners(string $event): bool
    {
        if (empty($event))
        {
            throw new \InvalidArgumentException('Event name cannot be empty.');
        }
        return isset($this->events[\strtolower($event)]);
    }

    /**
     * Get listeners
     * 
     * @param  string $event
     * @return array
     */
    function getEventListeners(string $event): array
    {
        if (empty($event))
        {
            throw new \InvalidArgumentException('Event name cannot be empty.');
        }
        $key = \strtolower($event);
        if ($this->hasListeners($event))
        {
            if (!isset($this->sorted[$key]))
            {
                $this->sortListeners($event);
            }
            return $this->sorted[$key];
        }
        return [];
    }

    /**
     * Get registered events
     * 
     * @return array of event names
     */
    function getEvents(): array
    {
        return $this->events;
    }

    /**
     * Sorts the internal list of listeners for the given event by priority.
     *
     * @param string $eventName The name of the event
     */
    private function sortListeners(string $eventName): void
    {
        $key = \strtolower($eventName);
        \krsort($this->events[$key]);
        $this->sorted[$key] = \call_user_func_array('array_merge',
                                                    $this->events[$key]);
    }

}
