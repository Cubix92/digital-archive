<?php

namespace Application\Factory;

use Application\Controller\IndexController;
use Application\Model\NoteHydrator;
use Application\Model\NoteRepository;
use Interop\Container\ContainerInterface;
use Zend\Mvc\Controller\AbstractActionController;

class IndexControllerFactory extends AbstractActionController
{
    public function __invoke(ContainerInterface $container)
    {
        $noteRepository = $container->get(NoteRepository::class);
        $noteHydrator = $container->get(NoteHydrator::class);

        return new IndexController($noteRepository, $noteHydrator);
    }
}
