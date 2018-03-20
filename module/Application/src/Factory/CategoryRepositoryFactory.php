<?php

namespace Application\Factory;

use Application\Model\CategoryHydrator;
use Application\Model\CategoryRepository;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;

class CategoryRepositoryFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $dbAdapter = $container->get(AdapterInterface::class);
        $categoryHydrator = $container->get(CategoryHydrator::class);

        return new CategoryRepository($dbAdapter, $categoryHydrator);
    }
}
