<?php
namespace User\Factory;

use User\Model\User;
use User\Form\UserForm;
use User\Form\UserInputFilter;
use Interop\Container\ContainerInterface;
use User\Model\UserHydrator;

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