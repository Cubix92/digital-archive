<?php
namespace Application\Factory;

use Application\Form\CategoryForm;
use Application\Model\Category;
use Application\Model\CategoryHydrator;
use Interop\Container\ContainerInterface;

class CategoryFormFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $form = new CategoryForm('category');
        $form->setHydrator($container->get(CategoryHydrator::class));
//        $form->setInputFilter($container->get('InputFilterManager')->get(CategoryInputFilter::class));
        $form->setObject(new Category());

        return $form;
    }
}