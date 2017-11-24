<?php

namespace Application\Factory;

use Application\Form\NoteForm;
use Application\Model\CategoryRepository;
use Application\Model\Note;
use Application\Model\NoteHydrator;
use Interop\Container\ContainerInterface;
use Zend\Hydrator\ClassMethods;

class NoteFormFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $categoryRepository = $container->get(CategoryRepository::class);
        $categories = $categoryRepository->findAll();
        $form = new NoteForm($categories);
        $form->setHydrator($container->get(NoteHydrator::class));
//        $form->setInputFilter($container->get('InputFilterManager')->get(CategoryInputFilter::class));
        $form->setObject(new Note());

        return $form;
    }
}