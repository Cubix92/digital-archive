<?php

namespace Application\Factory;

use Application\Model\Tag;
use Application\Model\TagHydrator;
use Application\Model\TagTable;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;

class TagTableFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $tagHydrator = $container->get(TagHydrator::class);
        $resultSetPrototype = new HydratingResultSet($tagHydrator, new Tag());
        $dbAdapter = $container->get(AdapterInterface::class);
        $tableGateway = new TableGateway('tag', $dbAdapter, null, $resultSetPrototype);

        return new TagTable($tableGateway, $tagHydrator);
    }
}
