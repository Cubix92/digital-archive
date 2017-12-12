<?php

namespace Application\Factory;

use Application\Controller\NoteController;
use Application\Form\NoteForm;
use Application\Service\TagService;
use Application\Model\NoteCommand;
use Application\Model\NoteRepository;
use Interop\Container\ContainerInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\FormElementManager;

class NoteControllerFactory extends AbstractActionController
{
    public function __invoke(ContainerInterface $container)
    {
        $noteRepository = $container->get(NoteRepository::class);
        $noteCommand = $container->get(NoteCommand::class);
        $noteForm = $container->get(FormElementManager::class)->get(NoteForm::class);

        return new NoteController($noteRepository, $noteCommand, $noteForm);
    }
}
