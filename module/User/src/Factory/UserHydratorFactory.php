<?php

namespace User\Factory;

use User\Model\UserHydrator;
use Interop\Container\ContainerInterface;

class UserHydratorFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new UserHydrator();
    }
}
