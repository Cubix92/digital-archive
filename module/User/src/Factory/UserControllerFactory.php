<?php

namespace User\Factory;

use User\Controller\UserController;
use User\Form\UserForm;
use User\Model\UserTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\FormElementManager;
use Interop\Container\ContainerInterface;

class UserControllerFactory extends AbstractActionController
{
    public function __invoke(ContainerInterface $container)
    {
        $userTable = $container->get(UserTable::class);
        $userForm = $container->get(FormElementManager::class)->get(UserForm::class);

        return new UserController($userTable, $userForm);
    }
}
