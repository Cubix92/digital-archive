<?php

namespace Application\Factory;

use Application\Model\Note;
use Application\Model\NoteHydrator;
use Application\Model\NoteTable;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;

class NoteTableFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $noteHydrator = $container->get(NoteHydrator::class);
        $resultSetPrototype = new HydratingResultSet($noteHydrator, new Note());
        $dbAdapter = $container->get(AdapterInterface::class);
        $tableGateway = new TableGateway('note', $dbAdapter, null, $resultSetPrototype);

        return new NoteTable($tableGateway);
    }
}
