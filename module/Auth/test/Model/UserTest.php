<?php

namespace UserTest\Controller;

use Auth\Model\User;
use PHPUnit\Framework\TestCase;
use Zend\Crypt\Password\Bcrypt;

class UserTest extends TestCase
{
    public function testInitialValuesAreDefault()
    {
        $user = new User();

        $this->assertNull($user->getId());
        $this->assertNull($user->getEmail());
        $this->assertNull($user->getRole());
        $this->assertNull($user->getPassword());
    }

    public function testSetsPropertiesCorrectly()
    {
        $user = (new User())
            ->setId(1)
            ->setEmail('example@example.com')
            ->setPassword('test1234')
            ->setRole('admin');

        $bcrypt = new Bcrypt();

        $this->assertEquals(1, $user->getId());
        $this->assertEquals('example@example.com', $user->getEmail());
        $this->assertEquals('admin', $user->getRole());
        $this->assertTrue($bcrypt->verify('test1234', $user->getPassword()));
    }
}
