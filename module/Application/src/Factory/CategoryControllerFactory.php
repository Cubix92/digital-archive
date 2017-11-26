<?php

namespace Application\Factory;

use Application\Controller\CategoryController;
use Application\Form\CategoryForm;
use Application\Model\CategoryAdapter;
use Application\Model\CategoryRepository;
use Interop\Container\ContainerInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\FormElementManager;

class CategoryControllerFactory extends AbstractActionController
{
    public function __invoke(ContainerInterface $container)
    {
        $categoryRepository = $container->get(CategoryRepository::class);
        $categoryCommand = $container->get(CategoryAdapter::class);
        $categoryForm = $container->get(FormElementManager::class)->get(CategoryForm::class);

        return new CategoryController($categoryRepository, $categoryCommand, $categoryForm);
    }
}
