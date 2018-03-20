<?php

namespace Log\Factory;

use Log\Model\Log;
use Log\Model\LogHydrator;
use Log\Model\LogTable;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;

class LogTableFactory
{
    public function __invoke(ContainerInterface $container): LogTable
    {
        $dbAdapter = $container->get(AdapterInterface::class);
        $logHydrator = $container->get(LogHydrator::class);

        $resultSetPrototype = new HydratingResultSet($logHydrator, new Log());
        $tableGateway = new TableGateway('log', $dbAdapter, null, $resultSetPrototype);

        return new LogTable($tableGateway);
    }
}