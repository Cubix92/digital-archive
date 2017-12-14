<?php

namespace Application\Factory\Api;

use Application\Controller\Api\NoteController;
use Application\Model\NoteCommand;
use Application\Model\NoteRepository;
use Interop\Container\ContainerInterface;
use Zend\Mvc\Controller\AbstractActionController;

class NoteControllerFactory extends AbstractActionController
{
    public function __invoke(ContainerInterface $container)
    {
        $noteRepository = $container->get(NoteRepository::class);
        $noteCommand = $container->get(NoteCommand::class);

        return new NoteController($noteRepository, $noteCommand);
    }
}
