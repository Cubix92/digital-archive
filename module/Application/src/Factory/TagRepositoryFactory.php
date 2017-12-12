<?php

namespace Application\Factory;

use Application\Model\TagRepository;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;

class TagRepositoryFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $dbAdapter = $container->get(AdapterInterface::class);

        return new TagRepository($dbAdapter);
    }
}
