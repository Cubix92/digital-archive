<?php

namespace UserTest\Controller;

use Auth\Model\User;
use Auth\Model\UserTable;
use PHPUnit\Framework\TestCase;
use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Db\TableGateway\TableGateway;

class UserTableTest extends TestCase
{
    protected $tableGateway;

    protected $userTable;

    protected function setUp()
    {
        $this->tableGateway = $this->prophesize(TableGateway::class);
        $this->userTable = new UserTable($this->tableGateway->reveal());
    }

    public function testFetchAllReturnsAllAlbums()
    {
        $resultSet = $this->prophesize(ResultSetInterface::class)->reveal();
        $this->tableGateway->select()->willReturn($resultSet);

        $this->assertSame($resultSet, $this->userTable->fetchAll());
    }
}
