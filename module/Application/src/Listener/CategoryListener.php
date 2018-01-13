<?php

namespace Application\Listener;

use Zend\Authentication\AuthenticationService as AuthService;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\Log\Logger;
use Zend\Mvc\Controller\AbstractController;

class CategoryListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    protected $logger;

    protected $authService;

    public function __construct(Logger $logger, AuthService $authService)
    {
        $this->logger = $logger;
        $this->authService = $authService;
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $sharedManager = $events->getSharedManager();

        $this->listeners[] = $sharedManager->attach(
            AbstractController::class,
            'categoryAdded',
            [$this, 'onCategoryAdded'],
            $priority
        );

        $this->listeners[] = $sharedManager->attach(
            AbstractController::class,
            'categoryEdited',
            [$this, 'onCategoryEdited'],
            $priority
        );

        $this->listeners[] = $sharedManager->attach(
            AbstractController::class,
            'categoryDeleted',
            [$this, 'onCategoryDeleted'],
            $priority
        );
    }

    public function onCategoryAdded(EventInterface $e)
    {
        $category = $e->getParam('category');
        $message = sprintf('[{email}]: Add new category with name "%s"', $category->getName());
        $params['email'] = $this->authService->getIdentity()->email;

        $this->logger->info($message, $params);
    }

    public function onCategoryEdited(EventInterface $e)
    {
        $category = $e->getParam('category');
        $message = sprintf('[{email}]: Edit category "%s"', $category->getName());
        $params['email'] = $this->authService->getIdentity()->email;

        $this->logger->info($message, $params);
    }

    public function onCategoryDeleted(EventInterface $e)
    {
        $category = $e->getParam('category');
        $message = sprintf('[{email}]: Category "%s" was deleted', $category->getName());
        $params['email'] = $this->authService->getIdentity()->email;

        $this->logger->info($message, $params);
    }
}