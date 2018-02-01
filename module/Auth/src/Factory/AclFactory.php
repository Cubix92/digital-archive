<?php

namespace Auth\Factory;

use Interop\Container\ContainerInterface;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\GenericResource;
use Zend\Permissions\Acl\Role\GenericRole;
use Zend\ServiceManager\Factory\FactoryInterface;

class AclFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): Acl
    {
        $acl = new Acl();

        $roles = $container->get('Config')['acl'];

        foreach ($roles as $role => $resources) {
            $role = new GenericRole($role);
            $acl->addRole($role);

            foreach ($resources as $resource) {
                if(!$acl->hasResource($resource)) {
                    $acl->addResource(new GenericResource($resource));
                }

                $acl->allow($role, $resource);
            }
        }

        return $acl;
    }
}