<?php

namespace Application\Factory;

use Application\Form\NoteInputFilter;
use Application\Model\CategoryRepository;
use Interop\Container\ContainerInterface;
use Zend\Mvc\Controller\AbstractActionController;

class NoteInputFilterFactory extends AbstractActionController
{
    public function __invoke(ContainerInterface $container)
    {
        $categoryRepository = $container->get(CategoryRepository::class);
        $categories = [];

        /**
         * @vat Category $category
         */
        foreach((array)$categoryRepository->findAll() as $category) {
            $categories[] = $category->getId();
        }

        return new NoteInputFilter($categories);
    }
}
