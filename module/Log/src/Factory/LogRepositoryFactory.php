<?php

namespace Log\Factory;

use Log\Model\LogRepository;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;

class LogRepositoryFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $dbAdapter = $container->get(AdapterInterface::class);

        return new LogRepository($dbAdapter);
    }
}
