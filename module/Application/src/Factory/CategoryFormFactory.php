<?php

namespace Application\Factory;

use Application\Form\CategoryForm;
use Application\Model\Category;
use Interop\Container\ContainerInterface;
use Zend\Hydrator\ClassMethods;

class CategoryFormFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $form = new CategoryForm('category');
        $form->setHydrator($container->get('HydratorManager')->get(ClassMethods::class));
//        $form->setInputFilter($container->get('InputFilterManager')->get(CategoryInputFilter::class));
        $form->setObject(new Category());

        return $form;
    }
}