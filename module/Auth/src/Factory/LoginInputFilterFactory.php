<?php

namespace Auth\Factory;

use Auth\Form\LoginInputFilter;
use Interop\Container\ContainerInterface;
use Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter as AuthAdapter;
use Zend\Mvc\Controller\AbstractActionController;

class LoginInputFilterFactory extends AbstractActionController
{
    public function __invoke(ContainerInterface $container)
    {
        return new LoginInputFilter();
    }
}
