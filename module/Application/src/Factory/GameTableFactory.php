<?php

namespace User\Factory;

use Interop\Container\ContainerInterface;
use User\Model\Game;
use User\Model\GameTable;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Hydrator\Reflection;
use Zend\Hydrator\Reflection as ReflectionHydrator;

class GameTableFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $resultSetPrototype = new HydratingResultSet(new ReflectionHydrator(), new Game());
        $dbAdapter = $container->get(AdapterInterface::class);
        $tableGateway = new TableGateway('game', $dbAdapter, null, $resultSetPrototype);

        return new GameTable($tableGateway);
    }
}
