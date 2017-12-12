<?php

namespace Application\Factory;

use Application\Listener\TagListener;
use Application\Model\TagCommand;
use Application\Model\TagRepository;
use Interop\Container\ContainerInterface;

class TagListenerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $tagRepository = $container->get(TagRepository::class);
        $tagCommand = $container->get(TagCommand::class);

        return new TagListener($tagRepository, $tagCommand);
    }
}
