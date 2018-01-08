<?php

namespace Application\Factory\Api;

use Application\Controller\Api\TagController;
use Application\Model\TagCommand;
use Application\Model\TagHydrator;
use Application\Model\TagRepository;
use Interop\Container\ContainerInterface;
use Zend\Mvc\Controller\AbstractActionController;

class TagControllerFactory extends AbstractActionController
{
    public function __invoke(ContainerInterface $container)
    {
        $tagRepository = $container->get(TagRepository::class);
        $tagCommand = $container->get(TagCommand::class);
        $tagHydrator = $container->get(TagHydrator::class);

        return new TagController($tagRepository, $tagCommand, $tagHydrator);
    }
}
