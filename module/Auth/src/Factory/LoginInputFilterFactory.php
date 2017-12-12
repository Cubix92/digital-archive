<?php

namespace Auth\Factory;

use Auth\Form\LoginInputFilter;
use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService as AuthService;
use Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter as AuthAdapter;
use Zend\Mvc\Controller\AbstractActionController;

class LoginInputFilterFactory extends AbstractActionController
{
    public function __invoke(ContainerInterface $container)
    {
        $authService = $container->get(AuthService::class);
        $authAdapter = $container->get(AuthAdapter::class);

        return new LoginInputFilter($authService, $authAdapter);
    }
}
