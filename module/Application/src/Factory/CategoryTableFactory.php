<?php

namespace Application\Factory;

use Application\Model\Category;
use Application\Model\CategoryHydrator;
use Application\Model\CategoryTable;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;

class CategoryTableFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $categoryHydrator = $container->get(CategoryHydrator::class);
        $resultSetPrototype = new HydratingResultSet($categoryHydrator, new Category());
        $dbAdapter = $container->get(AdapterInterface::class);
        $tableGateway = new TableGateway('category', $dbAdapter, null, $resultSetPrototype);

        return new CategoryTable($tableGateway, $categoryHydrator);
    }
}
