<?php

namespace UserTest\Controller;

use Auth\Model\User;
use Auth\Model\UserTable;
use Log\Model\Log;
use Log\Model\LogHydrator;
use PHPUnit\Framework\TestCase;
use Zend\Crypt\Password\Bcrypt;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Db\TableGateway\TableGatewayInterface;

class LogHydratorTest extends TestCase
{
    protected $traceError = true;

    protected $tableGateway;

    protected $userTable;

    protected function setUp()
    {
        $this->tableGateway = $this->prophesize(TableGatewayInterface::class);
    }

    public function testHydrateReturnsLogObjectWithValidData()
    {
        $now = new \DateTime();

        $logData = [
            'id' => 123,
            'content' => 'Lorem ipsum dolor sit amet...',
            'type' => 5,
            'date' => $now->format('Y-m-d'),
        ];

        $logHydrator = new LogHydrator();
        $log = $logHydrator->hydrate($logData, new Log());

        $this->assertEquals(123, $log->getId());
        $this->assertEquals('Lorem ipsum dolor sit amet...', $log->getContent());
        $this->assertEquals(5, $log->getType());
        $this->assertInstanceOf(\DateTime::class, $log->getDate());
    }

    public function testExtractReturnsLogArrayWithValidData()
    {
        $now = new \DateTime();

        $log = (new Log())
            ->setId(123)
            ->setContent('Lorem ipsum dolor sit amet...')
            ->setType(5)
            ->setDate($now);

        $logHydrator = new LogHydrator();
        $logData = $logHydrator->extract($log);

        $this->assertInternalType('array', $logData);
        $this->assertEquals(123, $logData['id']);
        $this->assertEquals('Lorem ipsum dolor sit amet...', $logData['content']);
        $this->assertEquals(5, $logData['type']);
        $this->assertEquals($now->format('Y-m-d H:i:s'), $logData['date']);
    }
}
