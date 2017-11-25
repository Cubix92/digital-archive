<?php

namespace Application\Factory;

use Application\Controller\TagController;
use Application\Model\TagRepository;
use Interop\Container\ContainerInterface;
use Zend\Mvc\Controller\AbstractActionController;

class TagControllerFactory extends AbstractActionController
{
    public function __invoke(ContainerInterface $container)
    {
        $tagRepository = $container->get(TagRepository::class);

        return new TagController($tagRepository);
    }
}
