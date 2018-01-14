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

class AuthListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    protected $logger;

    protected $authService;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $sharedManager = $events->getSharedManager();

        $this->listeners[] = $sharedManager->attach(
            AbstractActionController::class,
            'userAdded',
            [$this, 'onUserAdded'],
            $priority
        );

        $this->listeners[] = $sharedManager->attach(
            AbstractActionController::class,
            'userEdited',
            [$this, 'onUserEdited'],
            $priority
        );

        $this->listeners[] = $sharedManager->attach(
            AbstractActionController::class,
            'userDeleted',
            [$this, 'onUserDeleted'],
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

    public function onUserAdded(EventInterface $e)
    {
        $user = $e->getParam('user');
        $message = sprintf('[{email}]: Add new user "%s"', $user->getEmail());
        $params['email'] = $e->getTarget()->identity()->email;

        $this->logger->info($message, $params);
    }

    public function onUserEdited(EventInterface $e)
    {
        $user = $e->getParam('user');
        $message = sprintf('[{email}]: Edit user "%s"', $user->getEmail());
        $params['email'] = $e->getTarget()->identity()->email;

        $this->logger->info($message, $params);
    }

    public function onUserDeleted(EventInterface $e)
    {
        $user = $e->getParam('user');
        $message = sprintf('[{email}]: User "%s" was deleted', $user->getEmail());
        $params['email'] = $e->getTarget()->identity()->email;

        $this->logger->info($message, $params);
    }
}