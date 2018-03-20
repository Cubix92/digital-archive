<?php

namespace Auth;

use Auth\Listener\AuthListener;
use Auth\Listener\UserListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $eventManager = $e->getTarget()->getEventManager();

        $authListener = $serviceManager->get(AuthListener::class );
        $authListener->attach($eventManager);

        $userListener = $serviceManager->get(UserListener::class );
        $userListener->attach($eventManager);
    }

    public function getModuleDependencies()
    {
        return [
            'Log'
        ];
    }
}
