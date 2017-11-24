<?php

namespace Auth;

use Auth\Controller\AuthController;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();

        $sharedEventManager->attach(AbstractActionController::class,
            MvcEvent::EVENT_DISPATCH, [$this, 'checkIdentity'], 100
        );
    }

    public function checkIdentity(MvcEvent $e)
    {
        $controller = $e->getTarget();
        $authService = $e->getApplication()->getServiceManager()->get(AuthenticationService::class);
        $controllerName = $e->getRouteMatch()->getParam('controller', null);

        if (!$authService->getIdentity() && $controllerName != AuthController::class) {
            return $controller->redirect()->toRoute('login');
        }
    }
}
