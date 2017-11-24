<?php

namespace Application\Factory;

use Application\Model\NoteCommand;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Mvc\Controller\AbstractActionController;

class NoteCommandFactory extends AbstractActionController
{
    public function __invoke(ContainerInterface $container)
    {
        $dbAdapter = $container->get(AdapterInterface::class);

        return new NoteCommand($dbAdapter);
    }
}
