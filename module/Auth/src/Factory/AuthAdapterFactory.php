<?php

namespace Auth\Factory;

use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Crypt\Password\Bcrypt;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter as AuthAdapter;

class AuthAdapterFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $dbAdapter = $container->get(AdapterInterface::class);
        $passwordValidation = function ($hash, $password) {
            $bcrypt = new Bcrypt;
            return $bcrypt->verify($password, $hash);
        };

        return new AuthAdapter($dbAdapter, 'user', 'email', 'password', $passwordValidation);
    }
}