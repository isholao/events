<?php

namespace Isholao\Events;

/**
 *
 * @author Ishola O <ishola.tolu@outlook.com>
 */
interface EventInterface extends \ArrayAccess
{

    function setName(string $name): Event;

    function getName(): string;

    function setData(array $data): Event;

    public function getData(): array;

    public function stop(): bool;

    public function hasStopped(): bool;
    
    public function canStop(): bool;
}
