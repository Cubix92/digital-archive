<?php

namespace Application;

use Application\Listener\TagListener;
use Zend\Mvc\MvcEvent;

class Module
{
    const VERSION = '3.0.3-dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        $viewModel = $e->getApplication()->getMvcEvent()->getViewModel();
        $viewModel->isHomePage = $e->getRequest()->getUri()->getPath() == '/';

        $serviceManager = $e->getApplication()->getServiceManager();
        $eventManager = $e->getTarget()->getEventManager();

        $tagListener = $serviceManager->get(TagListener::class);
        $tagListener->attach($eventManager);
    }

    public function getModuleDependencies()
    {
        return [
            'Auth'
        ];
    }
}
