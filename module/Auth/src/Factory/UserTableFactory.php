<?php

namespace Auth\Factory;

use Auth\Model\User;
use Auth\Model\UserTable;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Hydrator\Reflection as ReflectionHydrator;

class UserTableFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $resultSetPrototype = new HydratingResultSet(new ReflectionHydrator(), new User());
        $dbAdapter = $container->get(AdapterInterface::class);
        $tableGateway = new TableGateway('user', $dbAdapter, null, $resultSetPrototype);

        return new UserTable($tableGateway);
    }
}
