<?php

namespace Application\Factory;

use Application\Model\NoteCommand;
use Application\Service\TagService;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Mvc\Controller\AbstractActionController;

class NoteCommandFactory extends AbstractActionController
{
    public function __invoke(ContainerInterface $container)
    {
        $dbAdapter = $container->get(AdapterInterface::class);
        $tagService = $container->get(TagService::class);

        return new NoteCommand($dbAdapter, $tagService);
    }
}
