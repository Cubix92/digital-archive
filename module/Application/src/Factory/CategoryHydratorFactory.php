<?php

namespace Application\Factory;

use Application\Model\CategoryHydrator;

use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;

class CategoryHydratorFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $dbAdapter = $container->get(AdapterInterface::class);

        return new CategoryHydrator($dbAdapter);
    }
}
