<?php

namespace UserTest\Controller;

use Auth\Model\User;
use Auth\Model\UserTable;
use PHPUnit\Framework\TestCase;
use Zend\Crypt\Password\Bcrypt;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Db\TableGateway\TableGatewayInterface;

class UserTableTest extends TestCase
{
    protected $traceError = true;

    protected $tableGateway;

    protected $userTable;

    protected function setUp()
    {
        $this->tableGateway = $this->prophesize(TableGatewayInterface::class);
        $this->userTable = new UserTable($this->tableGateway->reveal());
    }

    public function testFetchAllReturnsAllUsers()
    {
        $resultSet = $this->prophesize(ResultSetInterface::class)->reveal();
        $this->tableGateway->select()->willReturn($resultSet);

        $this->assertSame($resultSet, $this->userTable->fetchAll());
    }

    public function testCanDeleteAnUserByItsId()
    {
        $this->tableGateway->delete(['id' => 123])->shouldBeCalled();
        $this->userTable->delete(123);
    }

    public function testSaveUserWillInsertNewUserIfTheyDontAlreadyHaveAnId()
    {
        $userData = [
            'email' => 'example@example.com',
            'role' => 'admin',
            'password' => (new Bcrypt())->create('test_pass'),
            'date_created' => (new \DateTime())->format('Y-m-d H:i:s')
        ];

        $hydrator = new ReflectionHydrator();
        $user = $hydrator->hydrate($userData, new User());

        $this->tableGateway->insert($userData)->shouldBeCalled();
        $this->userTable->save($user);
    }

    public function testSaveUserWillUpdateExistingUserIfTheyAlreadyHaveAnId()
    {
        $userData = [
            'id' => 123,
            'email' => 'example@example.com',
            'role' => 'admin',
            'password' => (new Bcrypt())->create('test_pass'),
            'date_created' => (new \DateTime())->format('Y-m-d H:i:s')
        ];

        $hydrator = new ReflectionHydrator();
        $user = $hydrator->hydrate($userData, new User());

        $resultSet = $this->prophesize(ResultSetInterface::class);
        $resultSet->current()->willReturn($user);

        $this->tableGateway
            ->select(['id' => 123])
            ->willReturn($resultSet->reveal());

        $this->tableGateway
            ->update(
                array_filter($userData, function ($key) {
                    return in_array($key, ['email', 'role', 'password']);
                }, ARRAY_FILTER_USE_KEY),
                ['id' => 123]
            )->shouldBeCalled();

        $this->userTable->save($user);
    }

    public function testExceptionIsThrownWhenGettingNonExistentUser()
    {
        $resultSet = $this->prophesize(ResultSetInterface::class);
        $resultSet->current()->willReturn(null);

        $this->tableGateway
            ->select(['id' => 123])
            ->willReturn($resultSet->reveal());

        $this->expectException(\RuntimeException::class);
        $this->userTable->getUser(123);
    }
}
