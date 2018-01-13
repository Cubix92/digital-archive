<?php

namespace Auth\Factory;

use Auth\Listener\AuthListener;
use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService as AuthService;
use Zend\Log\Logger;
use Zend\ServiceManager\Factory\FactoryInterface;

class AuthListenerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $logger = $container->get(Logger::class);
        $authService = $container->get(AuthService::class);

        return new AuthListener($logger, $authService);
    }
}