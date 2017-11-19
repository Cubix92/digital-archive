<?php

namespace User\Factory;

use Interop\Container\ContainerInterface;
use User\Model\User;
use User\Model\UserHydrator;
use User\Model\UserTable;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class UserTableFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $userHydrator = $container->get(UserHydrator::class);
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new HydratingResultSet($userHydrator, new User());
        $tableGateway = new TableGateway('user', $dbAdapter, null, $resultSetPrototype);

        return new UserTable($tableGateway, $userHydrator);
    }
}
