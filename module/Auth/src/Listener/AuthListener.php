<?php

namespace Auth\Listener;

use Auth\Controller\AuthController;
use Zend\Authentication\AuthenticationService;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\MvcEvent;

class AuthListener implements ListenerAggregateInterface
{
    private $listeners = [];

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_DISPATCH,
            [$this, 'checkIdentity'],
            $priority
        );
    }

    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            $events->detach($listener);
            unset($this->listeners[$index]);
        }
    }

    public function checkIdentity(EventInterface $event)
    {
        $controller = $event->getTarget();
        $authService = $event->getApplication()->getServiceManager()->get(AuthenticationService::class);
        $controllerName = $event->getRouteMatch()->getParam('controller', null);

        if (!$authService->getIdentity() && $controllerName != AuthController::class) {
            return $controller->redirect()->toRoute('login');
        }

        return 0;
    }
}