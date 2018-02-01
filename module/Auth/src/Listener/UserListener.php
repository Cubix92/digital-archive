<?php

namespace Auth\Listener;

use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\Log\Logger;
use Zend\Mvc\Controller\AbstractActionController;

class UserListener implements ListenerAggregateInterface
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