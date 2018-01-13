<?php

namespace Application\Factory;

use Application\Listener\CategoryListener;
use Zend\Authentication\AuthenticationService as AuthService;
use Interop\Container\ContainerInterface;
use Zend\Log\Logger;

class CategoryListenerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $logger = $container->get(Logger::class);
        $authService = $container->get(AuthService::class);

        return new CategoryListener($logger, $authService);
    }
}
