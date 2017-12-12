<?php

namespace Application\Factory;

use Application\Service\TagService;
use Application\Model\TagCommand;
use Application\Model\TagRepository;
use Interop\Container\ContainerInterface;
use Zend\Mvc\Controller\AbstractActionController;

class TagServiceFactory extends AbstractActionController
{
    public function __invoke(ContainerInterface $container)
    {
        $tagRepository = $container->get(TagRepository::class);
        $tagCommand = $container->get(TagCommand::class);

        return new TagService($tagRepository, $tagCommand);
    }
}
