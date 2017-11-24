<?php

namespace Application\Factory;

use Application\Controller\CategoryController;
use Application\Form\CategoryForm;
use Application\Model\CategoryRepository;
use Application\Model\CategoryTable;
use Interop\Container\ContainerInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\FormElementManager;

class CategoryControllerFactory extends AbstractActionController
{
    public function __invoke(ContainerInterface $container)
    {
        $categoryRepository = $container->get(CategoryRepository::class);
        $categoryForm = $container->get(FormElementManager::class)->get(CategoryForm::class);


        return new CategoryController($categoryRepository, $categoryForm);
    }
}
