<?php

namespace Application\Listener;

use Application\Model\TagCommand;
use Application\Model\TagRepository;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\Controller\AbstractController;

class TagListener implements ListenerAggregateInterface
{
    private $listeners = [];

    protected $tagCommand;

    protected $tagRepository;

    public function __construct(TagRepository $tagRepository, TagCommand $tagCommand)
    {
        $this->tagRepository = $tagRepository;
        $this->tagCommand = $tagCommand;
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $sharedManager = $events->getSharedManager();

        $this->listeners[] = $sharedManager->attach(
            AbstractController::class,
            'noteDeleted',
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

    public function onNoteChange()
    {
        $tags = $this->tagRepository->findUnassigned();

        foreach($tags as $tag) {
            $this->tagCommand->delete($tag);
        }
    }
}