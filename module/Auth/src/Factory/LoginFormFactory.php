<?php

namespace Auth\Factory;

use Auth\Form\LoginForm;
use Auth\Form\LoginInputFilter;
use Interop\Container\ContainerInterface;

class LoginFormFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $form = new LoginForm('login-form');
        $form->setInputFilter($container->get('InputFilterManager')->get(LoginInputFilter::class));

        return $form;
    }
}