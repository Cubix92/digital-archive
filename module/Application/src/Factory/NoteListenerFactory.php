<?php

namespace Application\Factory;

use Application\Listener\CategoryListener;
use Application\Listener\NoteListener;
use Zend\Authentication\AuthenticationService as AuthService;
use Interop\Container\ContainerInterface;
use Zend\Log\Logger;

class NoteListenerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $logger = $container->get(Logger::class);
        $authService = $container->get(AuthService::class);

        return new NoteListener($logger, $authService);
    }
}
