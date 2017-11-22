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
        $categoryForm = $container->get(FormElementManager::class)->get(CategoryForm::class);
        $categoryTable = $container->get(CategoryTable::class);
        $categoryRepository = $container->get(CategoryRepository::class);

        return new CategoryController($categoryRepository, $categoryTable, $categoryForm);
    }
}
