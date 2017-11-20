<?php
namespace Auth\Factory;

use Interop\Container\ContainerInterface;
use Auth\Model\User;
use Auth\Form\UserForm;
use Auth\Form\UserInputFilter;
use Auth\Model\UserHydrator;

class UserFormFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $form = new UserForm('user');
        $form->setHydrator($container->get(UserHydrator::class));
        $form->setInputFilter($container->get('InputFilterManager')->get(UserInputFilter::class));
        $form->setObject(new User());

        return $form;
    }
}