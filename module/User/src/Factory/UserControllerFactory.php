<?php

namespace User\Factory;

use User\Controller\UserController;
use User\Model\UserTable;
use Zend\Mvc\Controller\AbstractActionController;
use Interop\Container\ContainerInterface;

class UserControllerFactory extends AbstractActionController
{
    public function __invoke(ContainerInterface $container)
    {
        $userTable = $container->get(UserTable::class);

        return new UserController($userTable);
    }
}
