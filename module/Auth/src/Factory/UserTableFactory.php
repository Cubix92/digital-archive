<?php

namespace Auth\Factory;

use Auth\Model\User;
use Auth\Model\UserHydrator;
use Auth\Model\UserTable;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;

class UserTableFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $userHydrator = $container->get(UserHydrator::class);
        $resultSetPrototype = new HydratingResultSet($userHydrator, new User());
        $dbAdapter = $container->get(AdapterInterface::class);
        $tableGateway = new TableGateway('user', $dbAdapter, null, $resultSetPrototype);

        return new UserTable($tableGateway, $userHydrator);
    }
}
