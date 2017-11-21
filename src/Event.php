<?php

namespace Isholao\Events;

/**
 * @author Ishola O <ishola.tolu@outlook.com>
 */
final class Event implements EventInterface
{

    private $eventName;
    private $data = [];
    private $stopped = false;
    private $stoppable = false;

    function __construct(array $data = [], bool $stoppable = TRUE)
    {
        $this->data = $data;
        $this->stoppable = $stoppable;
    }

    /**
     * Set event name
     * 
     * @param string $name event name
     * @return Event
     */
    function setName(string $name): Event
    {
        if (empty($name))
        {
            throw new \InvalidArgumentException('Name cannot be empty.');
        }
        $this->eventName = $name;
        return $this;
    }

    /**
     * Get event name
     * 
     * @return string
     */
    function getName(): string
    {
        return $this->eventName;
    }

    /**
     * Attach data to event
     * 
     * @param array $data  data  to attach to an event
     * @return Event
     */
    function setData(array $data): Event
    {
        foreach ($data as $key => &$value)
        {
            $this->{$key} = $value;
        }
        return $this;
    }

    /**
     * Get attached data
     * 
     * @return array
     */
    function getData(): array
    {
        return $this->data;
    }

    function __get(string $name)
    {
        return $this->offsetGet($name);
    }

    function __set(string $name, $value): void
    {
        $this->offsetSet($name, $value);
    }

    function __isset(string $name): bool
    {
        return $this->offsetExists($name);
    }

    function __unset(string $name)
    {
        return $this->offsetUnset($name);
    }

    public function offsetExists($offset): bool
    {
        if (empty($offset))
        {
            throw new \InvalidArgumentException('Offset cannot be empty.');
        }
        return isset($this->data[\strtolower($offset)]);
    }

    public function offsetGet($offset)
    {
        if (empty($offset))
        {
            throw new \InvalidArgumentException('Offset cannot be empty.');
        }
        return $this->data[\strtolower($offset)] ?? NULL;
    }

    public function offsetSet($offset, $value): void
    {
        if (empty($offset))
        {
            throw new \InvalidArgumentException('Offset cannot be empty.');
        }
        $this->data[\strtolower($offset)] = $value;
    }

    public function offsetUnset($offset): void
    {
        if (empty($offset))
        {
            throw new \InvalidArgumentException('Offset cannot be empty.');
        }
        $this->data[\strtolower($offset)] = NULL;
        unset($this->data[\strtolower($offset)]);
    }

    /**
     * Stop the event
     * 
     * @throws \Error
     */
    public function stop(): bool
    {
        if ($this->stoppable)
        {
            return $this->stopped = TRUE;
        }
        throw new \Error('Trying to cancel a non-cancelable event');
    }

    /**
     * Check whether the event has stopped
     * 
     * @return bool
     */
    public function hasStopped(): bool
    {
        return $this->stopped;
    }

    /**
     * Check whether the event can be stopped.
     *
     * @return bool
     */
    public function canStop(): bool
    {
        return $this->stoppable;
    }

}
