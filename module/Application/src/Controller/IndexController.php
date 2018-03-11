<?php

namespace Application\Controller;

use Application\Model\Note;
use Application\Model\NoteHydrator;
use Application\Model\NoteRepository;
use Application\Model\TagRepository;
use Zend\Json\Json;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    protected $noteRepository;

    protected $noteHydrator;

    protected $tagRepository;

    public function __construct(NoteRepository $noteRepository, TagRepository $tagRepository, NoteHydrator $noteHydrator)
    {
        $this->noteRepository = $noteRepository;
        $this->noteHydrator = $noteHydrator;
        $this->tagRepository = $tagRepository;
    }

    public function indexAction()
    {
        return new ViewModel([
            'tags' => $this->tagRepository->findAll()
        ]);
    }
}
