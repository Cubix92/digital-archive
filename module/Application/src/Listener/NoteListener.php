<?php

namespace Application\Listener;

use Zend\Authentication\AuthenticationService as AuthService;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\Log\Logger;
use Zend\Mvc\Controller\AbstractController;

class NoteListener implements ListenerAggregateInterface
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
            'noteAdded',
            [$this, 'onNoteAdded'],
            $priority
        );

        $this->listeners[] = $sharedManager->attach(
            AbstractController::class,
            'noteEdited',
            [$this, 'onNoteEdited'],
            $priority
        );

        $this->listeners[] = $sharedManager->attach(
            AbstractController::class,
            'noteDeleted',
            [$this, 'onNoteDeleted'],
            $priority
        );
    }

    public function onNoteAdded(EventInterface $e)
    {
        $note = $e->getParam('note');
        $message = sprintf('[{email}]: Add new note with title "%s"', $note->getTitle());
        $params = ['email' => $this->authService->getIdentity()->email];

        $this->logger->info($message, $params);
    }

    public function onNoteEdited(EventInterface $e)
    {
        $note = $e->getParam('note');
        $message = sprintf('[{email}]: Edit note "%s"', $note->getTitle());
        $params = ['email' => $this->authService->getIdentity()->email];

        $this->logger->info($message, $params);
    }

    public function onNoteDeleted(EventInterface $e)
    {
        $note = $e->getParam('note');
        $message = sprintf('[{email}]: Delete note "%s"', $note->getTitle());
        $params = ['email' => $this->authService->getIdentity()->email];

        $this->logger->info($message, $params);
    }
}