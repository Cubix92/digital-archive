<?php

namespace Auth\Factory;

use Auth\Form\LoginForm;
use Interop\Container\ContainerInterface;

class LoginFormFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new LoginForm('login-form');
    }
}