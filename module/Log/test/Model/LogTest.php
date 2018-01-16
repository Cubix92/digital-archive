<?php

namespace LogTest\Controller;

use Log\Model\Log;
use PHPUnit\Framework\TestCase;

class LogTest extends TestCase
{
    public function testInitialValuesAreDefault()
    {
        $user = new Log();

        $this->assertNull($user->getId());
        $this->assertNull($user->getContent());
        $this->assertNull($user->getType());
        $this->assertNull($user->getDate());
    }

    public function testSetsPropertiesCorrectly()
    {
        $now = new \DateTime();

        $log = (new Log())
            ->setId(123)
            ->setContent('Lorem ipsum dolor sit amet...')
            ->setType(123)
            ->setDate($now);

        $this->assertEquals(123, $log->getId());
        $this->assertEquals('Lorem ipsum dolor sit amet...', $log->getContent());
        $this->assertEquals(123, $log->getType());
        $this->assertEquals($now, $log->getDate());
    }
}
