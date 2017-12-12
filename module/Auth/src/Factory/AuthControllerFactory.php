<?php

namespace Auth\Factory;

use Auth\Controller\AuthController;
use Auth\Form\LoginForm;
use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\FormElementManager;

class AuthControllerFactory extends AbstractActionController
{
    public function __invoke(ContainerInterface $container)
    {
        $loginForm = $container->get(FormElementManager::class)->get(LoginForm::class);
        $authService = $container->get(AuthenticationService::class);

        return new AuthController($loginForm, $authService);
    }
}
