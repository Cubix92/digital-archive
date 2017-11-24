<?php

namespace Application\Controller;

use Application\Form\NoteForm;
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

    public function __construct(NoteRepository $noteRepository, NoteCommand $noteCommand, NoteForm $noteForm)
    {
        $this->noteRepository = $noteRepository;
        $this->noteCommand = $noteCommand;
        $this->noteForm = $noteForm;
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
                $note = $form->getData();
                /** @var Note $note */
                $this->noteCommand->insert($note);
                $this->flashMessenger()->addSuccessMessage('Note was added successfull.');
                return $this->redirect()->toRoute('note');
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('note', ['action' => 'add']);
        }

        try {
            $note = $this->noteRepository->findById($id);
        } catch (\Exception $e) {
            $this->flashMessenger()->addSuccessMessage($e->getMessage());
            return $this->redirect()->toRoute('note', ['action' => 'index']);
        }

        $form = $this->noteForm;
        $form->bind($note);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setData($request->getPost());
            $this->flashMessenger()->addSuccessMessage('User was updated successfull.');
            if ($form->isValid()) {
                $this->noteCommand->save($note);
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
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('note');
        }

        try {
            $note = $this->noteRepository->findById($id);
        } catch (\Exception $e) {
            $this->flashMessenger()->addSuccessMessage($e->getMessage());
            return $this->redirect()->toRoute('note', ['action' => 'index']);
        }

        $this->noteCommand->delete($note);
        $this->flashMessenger()->addSuccessMessage('Note was deleted successfull.');
        return $this->redirect()->toRoute('note', ['action' => 'index']);
    }
}
