<?php

namespace Auth\Factory;

use Auth\Controller\UserController;
use Auth\Form\UserForm;
use Auth\Model\UserTable;
use Interop\Container\ContainerInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\FormElementManager;


class UserControllerFactory extends AbstractActionController
{
    public function __invoke(ContainerInterface $container)
    {
        $userTable = $container->get(UserTable::class);
        $userForm = $container->get(FormElementManager::class)->get(UserForm::class);

        return new UserController($userTable, $userForm);
    }
}
