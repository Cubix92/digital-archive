<?php

namespace User\Factory;

use Interop\Container\ContainerInterface;
use User\Model\User;
use User\Model\UserTable;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class UserTableFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $dbAdapter = $container->get(AdapterInterface::class);

        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new User());

        $tableGateway = new TableGateway('user', $dbAdapter, null, $resultSetPrototype);

        return new UserTable($tableGateway);
    }
}
