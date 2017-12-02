<?php

namespace Auth\Factory;

use Interop\Container\ContainerInterface;
use Auth\Form\UserInputFilter;
use Zend\Db\Adapter\AdapterInterface;

class UserInputFilterFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $dbAdapter = $container->get(AdapterInterface::class);

        return new UserInputFilter($dbAdapter);
    }
}