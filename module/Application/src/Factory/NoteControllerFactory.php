<?php

namespace Application\Factory;

use Application\Controller\NoteController;
use Application\Form\NoteForm;
use Application\Model\NoteTable;
use Interop\Container\ContainerInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\FormElementManager;

class NoteControllerFactory extends AbstractActionController
{
    public function __invoke(ContainerInterface $container)
    {
        $noteForm = $container->get(FormElementManager::class)->get(NoteForm::class);
        $noteTable = $container->get(NoteTable::class);

        return new NoteController($noteTable, $noteForm);
    }
}
