<?php

namespace Isholao\Events\Tests;

use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{

    public function testSetName()
    {
        $event = new \Isholao\Events\Event(['id' => 1], true);
        $event->setName('onBoot');
        $this->assertSame('onBoot', $event->getName());
    }

    public function testSetAndGetData()
    {
        $event = new \Isholao\Events\Event(['id' => 1], true);
        $event->id = 3;
        $this->assertSame(3, $event->id);

        unset($event->id);
        $this->assertSame(NULL, $event->id);
    }

}
