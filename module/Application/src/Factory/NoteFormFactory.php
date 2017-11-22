<?php
namespace Application\Factory;

use Application\Form\NoteForm;
use Application\Model\CategoryTable;
use Application\Model\Note;
use Application\Model\NoteHydrator;
use Interop\Container\ContainerInterface;

class NoteFormFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $categoryTable = $container->get(CategoryTable::class);
        $categories = $categoryTable->findAll();
        $form = new NoteForm($categories->toArray());
        $form->setHydrator($container->get(NoteHydrator::class));
//        $form->setInputFilter($container->get('InputFilterManager')->get(CategoryInputFilter::class));
        $form->setObject(new Note());

        return $form;
    }
}