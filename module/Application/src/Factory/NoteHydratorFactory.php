<?php

namespace Application\Factory;

use Application\Model\CategoryRepository;
use Application\Model\NoteHydrator;
use Interop\Container\ContainerInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\FormElementManager;

class NoteHydratorFactory extends AbstractActionController
{
    public function __invoke(ContainerInterface $container)
    {
        $categoryRepository = $container->get(CategoryRepository::class);

        return new NoteHydrator($categoryRepository);
    }
}
