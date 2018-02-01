<?php

namespace Auth\Listener;

use Zend\Authentication\AuthenticationService as AuthService;
use Auth\Controller\AuthController;
use Zend\Authentication\AuthenticationService;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\Log\Logger;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Mvc\MvcEvent;
use Zend\Permissions\Acl\Acl;

class AuthListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    protected $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $sharedManager = $events->getSharedManager();

        $this->listeners[] = $sharedManager->attach(
            '*',
            MvcEvent::EVENT_ROUTE,
            [$this, 'checkAcl'],
            $priority
        );

        $this->listeners[] = $sharedManager->attach(
            AbstractActionController::class,
            MvcEvent::EVENT_DISPATCH,
            [$this, 'checkIdentity'],
            $priority
        );

        $this->listeners[] = $sharedManager->attach(
            AbstractRestfulController::class,
            MvcEvent::EVENT_DISPATCH,
            [$this, 'checkToken'],
            $priority
        );
    }

    public function checkIdentity(EventInterface $event)
    {
        $controller = $event->getTarget();
        $authService = $event->getApplication()->getServiceManager()->get(AuthenticationService::class);
        $controllerName = $event->getRouteMatch()->getParam('controller', null);

        if (!$authService->hasIdentity() && $controllerName != AuthController::class) {
            return $controller->redirect()->toRoute('login');
        }

        return 0;
    }

    public function checkToken(EventInterface $event)
    {
        return 0;
    }

    public function checkAcl(MvcEvent $e) {
        $acl = $e->getApplication()->getServiceManager()->get(Acl::class);
        $authService = $e->getApplication()->getServiceManager()->get(AuthService::class);

        $route = $e->getRouteMatch()->getMatchedRouteName();
        $role = $authService->getIdentity() ? $authService->getIdentity()->role : 'subscriber';

        if ($acl->hasResource($route) && !$acl->isAllowed($role, $route)) {
            $response = $e->getResponse();
            $response->setStatusCode(404);
        }

        $e->getViewModel()->acl = $acl;
    }
}