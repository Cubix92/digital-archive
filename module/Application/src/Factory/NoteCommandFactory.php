<?php

namespace Application\Factory;

use Application\Model\NoteAdapter;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Mvc\Controller\AbstractActionController;

class NoteCommandFactory extends AbstractActionController
{
    public function __invoke(ContainerInterface $container)
    {
        $dbAdapter = $container->get(AdapterInterface::class);

        return new NoteAdapter($dbAdapter);
    }
}
