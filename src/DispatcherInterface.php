<?php

namespace Isholao\Events;

/**
 * @author Ishola O <ishola.tolu@outlook.com>
 */
interface DispatcherInterface
{

    public function on(string $event, $callback, int $priority = 0);

    public function addListener(EventListenerInterface $listener);

    public function notify(string $name, Event $event): Event;

    function hasListeners(string $event): bool;

    function getEventListeners(string $event): array;
    
    function getEvents(): array;
}
