<?php

namespace Application\Factory;

use Application\Model\NoteHydrator;
use Application\Model\NoteRepository;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Mvc\Controller\AbstractActionController;

class NoteRepositoryFactory extends AbstractActionController
{
    public function __invoke(ContainerInterface $container)
    {
        $noteHydrator = $container->get(NoteHydrator::class);
        $dbAdapter = $container->get(AdapterInterface::class);

        return new NoteRepository($noteHydrator, $dbAdapter);
    }
}
