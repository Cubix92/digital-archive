<?php

namespace Application;

use Application\Listener\CategoryListener;
use Application\Listener\NoteListener;
use Application\Listener\TagListener;
use Zend\Mvc\MvcEvent;
use Zend\Http\PhpEnvironment\Request as HttpRequest;

class Module
{
    const VERSION = '3.0.3-dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        if ($e->getRequest() instanceof HttpRequest) {
            $viewModel = $e->getApplication()->getMvcEvent()->getViewModel();
            $viewModel->isHomePage = $e->getRequest()->getUri()->getPath() == '/';
        }

        $serviceManager = $e->getApplication()->getServiceManager();
        $eventManager = $e->getTarget()->getEventManager();

        $tagListener = $serviceManager->get(TagListener::class);
        $tagListener->attach($eventManager);

        $categoryListener = $serviceManager->get(CategoryListener::class);
        $categoryListener->attach($eventManager);

        $noteListener = $serviceManager->get(NoteListener::class);
        $noteListener->attach($eventManager);
    }

    public function getModuleDependencies()
    {
        return [
            'Auth',
            'Log'
        ];
    }
}
