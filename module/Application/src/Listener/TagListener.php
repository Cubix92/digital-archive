<?php

namespace Application\Listener;

use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\Controller\AbstractController;

class TagListener implements ListenerAggregateInterface
{
    private $listeners = [];

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $sharedManager = $events->getSharedManager();

        $this->listeners[] = $sharedManager->attach(
            AbstractController::class,
            'noteAdded',
            [$this, 'onNoteChange'],
            $priority
        );

        $this->listeners[] = $sharedManager->attach(
            AbstractController::class,
            'noteEdited',
            [$this, 'onNoteChange'],
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

    public function onNoteChange(EventInterface $event)
    {
        var_dump('ok');die;
    }
}