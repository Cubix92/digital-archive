<?php

namespace Application\Controller;

use Application\Form\NoteForm;
use Application\Service\TagService;
use Application\Model\Note;
use Application\Model\NoteCommand;
use Application\Model\NoteRepository;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class NoteController extends AbstractActionController
{
    protected $noteRepository;

    protected $noteCommand;

    protected $noteForm;

    protected $tagService;

    public function __construct(NoteRepository $noteRepository, NoteCommand $noteCommand, NoteForm $noteForm, TagService $tagService)
    {
        $this->noteRepository = $noteRepository;
        $this->noteCommand = $noteCommand;
        $this->noteForm = $noteForm;
        $this->tagService = $tagService;
    }

    public function indexAction()
    {
        return new ViewModel([
            'notes' => $this->noteRepository->findAll()
        ]);
    }

    public function addAction()
    {
        $request = $this->getRequest();
        $form = $this->noteForm;

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                /** @var Note $note */
                $note = $form->getData();

                $tags = $this->tagService->prepare($note->getTags());
                $note->setTags($tags);
                $this->noteCommand->insert($note);

                $this->flashMessenger()->addSuccessMessage('Note was added successfull');
                return $this->redirect()->toRoute('note');
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }

    public function editAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->notFoundAction();
        }

        try {
            $note = $this->noteRepository->findById($id);
        } catch (\UnexpectedValueException $e) {
            $this->flashMessenger()->addErrorMessage('Note with identifier not found');
            return $this->redirect()->toRoute('note', ['action' => 'index']);
        }

        $form = $this->noteForm->bind($note);
        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $tags = $this->tagService->prepare($note->getTags());
                $note->setTags($tags);
                $this->noteCommand->update($note);

                $this->flashMessenger()->addSuccessMessage('Note was updated successfull');
                return $this->redirect()->toRoute('note', ['action' => 'index']);
            }
        }

        return new ViewModel([
            'note' => $note,
            'form' => $form
        ]);
    }

    public function deleteAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->notFoundAction();
        }

        try {
            $note = $this->noteRepository->findById($id);
        } catch (\UnexpectedValueException $e) {
            $this->flashMessenger()->addErrorMessage('Note with identifier not found');
            return $this->redirect()->toRoute('note', ['action' => 'index']);
        }

        $this->noteCommand->delete($note);
        $this->flashMessenger()->addSuccessMessage('Note was deleted successfull');
        return $this->redirect()->toRoute('note', ['action' => 'index']);
    }
}
