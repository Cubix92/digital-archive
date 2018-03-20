<?php

namespace Application\Factory;

use Application\Controller\LogController;
use Log\Model\LogTable;
use Interop\Container\ContainerInterface;
use Zend\Mvc\Controller\AbstractActionController;

class LogControllerFactory extends AbstractActionController
{
    public function __invoke(ContainerInterface $container)
    {
        $logTable = $container->get(LogTable::class);

        return new LogController($logTable);
    }
}
