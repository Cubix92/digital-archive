<?php

namespace Application\Factory;

use Application\Controller\IndexController;
use Application\Model\NoteHydrator;
use Application\Model\NoteRepository;
use Application\Model\TagRepository;
use Interop\Container\ContainerInterface;
use Zend\Mvc\Controller\AbstractActionController;

class IndexControllerFactory extends AbstractActionController
{
    public function __invoke(ContainerInterface $container)
    {
        $noteRepository = $container->get(NoteRepository::class);
        $tagRepository = $container->get(TagRepository::class);
        $noteHydrator = $container->get(NoteHydrator::class);

        return new IndexController($noteRepository, $tagRepository, $noteHydrator);
    }
}
