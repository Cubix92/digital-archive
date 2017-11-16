<?php
namespace User\Factory;

use User\Model\User;
use User\Form\UserForm;
use User\Form\UserInputFilter;
use Interop\Container\ContainerInterface;
use Zend\Hydrator\ClassMethods;

class UserFormFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $form = new UserForm('user');
        $form->setHydrator($container->get('HydratorManager')->get(ClassMethods::class));
        $form->setInputFilter($container->get('InputFilterManager')->get(UserInputFilter::class));
        $form->setObject(new User());

        return $form;
    }
}