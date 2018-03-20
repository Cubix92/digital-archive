<?php

namespace UserTest\Controller;

use Log\Model\LogTable;
use PHPUnit\Framework\TestCase;
use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Db\TableGateway\TableGateway;

class LogTableTest extends TestCase
{
    protected $traceError = true;

    protected $tableGateway;

    protected $logTable;

    protected function setUp()
    {
        $this->tableGateway = $this->prophesize(TableGateway::class);
        $this->logTable = new LogTable($this->tableGateway->reveal());
    }

    public function testFetchAllReturnsAllLogs()
    {
        $resultSet = $this->prophesize(ResultSetInterface::class)->reveal();
        $this->tableGateway->select()->willReturn($resultSet);

        $this->assertSame($resultSet, $this->logTable->fetchAll());
    }
}
