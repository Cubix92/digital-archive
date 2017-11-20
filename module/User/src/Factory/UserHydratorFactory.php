<?php

namespace User\Factory;

use User\Model\GameTable;
use User\Model\UserHydrator;
use Interop\Container\ContainerInterface;

class UserHydratorFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $gameTable = $container->get(GameTable::class);

        return new UserHydrator($gameTable);
    }
}
