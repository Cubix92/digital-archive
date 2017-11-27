<?php

namespace Application\Factory;

use Application\Model\CategoryCommand;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;

class CategoryCommandFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $dbAdapter = $container->get(AdapterInterface::class);

        return new CategoryCommand($dbAdapter);
    }
}
