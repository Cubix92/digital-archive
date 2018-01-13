<?php

namespace Application\Factory;

use Application\Controller\LogController;
use Log\Model\LogRepository;
use Interop\Container\ContainerInterface;
use Zend\Mvc\Controller\AbstractActionController;

class LogControllerFactory extends AbstractActionController
{
    public function __invoke(ContainerInterface $container)
    {
        $logRepository = $container->get(LogRepository::class);

        return new LogController($logRepository);
    }
}
