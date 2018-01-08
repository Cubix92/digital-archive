<?php

namespace Application\Factory\Api;

use Application\Controller\Api\CategoryController;
use Application\Model\CategoryCommand;
use Application\Model\CategoryHydrator;
use Application\Model\CategoryRepository;
use Interop\Container\ContainerInterface;
use Zend\Mvc\Controller\AbstractActionController;

class CategoryControllerFactory extends AbstractActionController
{
    public function __invoke(ContainerInterface $container)
    {
        $categoryRepository = $container->get(CategoryRepository::class);
        $categoryCommand = $container->get(CategoryCommand::class);
        $categoryHydrator = $container->get(CategoryHydrator::class);

        return new CategoryController($categoryRepository, $categoryCommand, $categoryHydrator);
    }
}
