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
        $dbAdapter = $container->get(AdapterInterface::class);
        $noteHydrator = $container->get(NoteHydrator::class);

        return new NoteRepository($dbAdapter, $noteHydrator);
    }
}
