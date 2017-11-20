<?php

namespace Auth\Factory;

use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Crypt\Password\Bcrypt;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter as AuthAdapter;

class AuthServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $sessionManager = $container->get(SessionManager::class);
        $authStorage = new SessionStorage('Zend_Auth', 'session', $sessionManager);

        $dbAdapter = $container->get(AdapterInterface::class);
        $passwordValidation = function ($hash, $password) {
            $bcrypt = new Bcrypt;
            return $bcrypt->verify($password, $hash);
        };
        $authAdapter = new AuthAdapter($dbAdapter, 'user', 'email', 'password', $passwordValidation);

        return new AuthenticationService($authStorage, $authAdapter);
    }
}