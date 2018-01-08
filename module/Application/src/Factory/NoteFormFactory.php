<?php

namespace Application\Factory;

use Application\Form\NoteForm;
use Application\Form\NoteInputFilter;
use Application\Model\Category;
use Application\Model\CategoryRepository;
use Application\Model\Note;
use Interop\Container\ContainerInterface;
use Zend\Hydrator\NamingStrategy\MapNamingStrategy;
use Zend\Hydrator\Strategy\ClosureStrategy;
use Zend\Hydrator\Reflection as ReflectionHydrator;

class NoteFormFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $categoryStrategy = new ClosureStrategy(
            function($object){
                return $object;
            },
            function($value){
                return (new Category())->setId($value);
            }
        );

        $dateStrategy = new ClosureStrategy(
            function($object){
                return $object;
            },
            function($value){
                return new \DateTime($value);
            }
        );

        $namingStrategy = new MapNamingStrategy(array(
            'date_published' => 'datePublished'
        ));

        $reflectionHydrator = (new ReflectionHydrator())
            ->setNamingStrategy($namingStrategy)
            ->addStrategy('category', $categoryStrategy)
            ->addStrategy('datePublished', $dateStrategy);

        $categoryRepository = $container->get(CategoryRepository::class);
        $categories = $categoryRepository->findAll();

        $form = new NoteForm($categories);
        $form->setHydrator($reflectionHydrator);
        $form->setInputFilter($container->get('InputFilterManager')->get(NoteInputFilter::class));
        $form->setObject(new Note());

        return $form;
    }
}