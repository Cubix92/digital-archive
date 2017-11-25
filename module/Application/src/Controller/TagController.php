<?php

namespace Application\Controller;

use Application\Form\NoteForm;
use Application\Model\Note;
use Application\Model\NoteCommand;
use Application\Model\NoteRepository;
use Application\Model\TagRepository;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class TagController extends AbstractActionController
{
    protected $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function indexAction()
    {
        return new ViewModel([
            'tags' => $this->tagRepository->findAll()
        ]);
    }

    public function showAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->notFoundAction();
        }

        try {
            $tag = $this->tagRepository->findById($id);
        } catch(\InvalidArgumentException $e) {
            $this->flashMessenger()->addSuccessMessage($e->getMessage());
            return $this->redirect()->toRoute('tag', ['action' => 'index']);
        }

        return new ViewModel([
            'tag' => $tag
        ]);
    }
}
