<?php

namespace Auth\Factory;

use Auth\Controller\AuthController;
use Auth\Form\LoginForm;
use Auth\Service\LoginService;
use Interop\Container\ContainerInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\FormElementManager;

class AuthControllerFactory extends AbstractActionController
{
    public function __invoke(ContainerInterface $container)
    {
        $loginForm = $container->get(FormElementManager::class)->get(LoginForm::class);
        $loginService = $container->get(LoginService::class);

        return new AuthController($loginForm, $loginService);
    }
}
