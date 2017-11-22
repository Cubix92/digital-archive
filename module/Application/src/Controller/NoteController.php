<?php

namespace Application\Controller;

use Application\Form\NoteForm;
use Application\Model\NoteTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class NoteController extends AbstractActionController
{
    protected $noteTable;

    protected $noteForm;

    public function __construct(NoteTable $noteTable, NoteForm $noteForm)
    {
        $this->noteTable = $noteTable;
        $this->noteForm = $noteForm;
    }

    public function indexAction()
    {
        return new ViewModel([
            'notes' => $this->noteTable->findAll()
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
                $this->noteTable->save($note);

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
            $note = $this->noteTable->findById($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('note', ['action' => 'index']);
        }

        $form = $this->noteForm;
        $form->bind($note);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->noteTable->save($note);
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

        $this->noteTable->delete($id);

        return $this->redirect()->toRoute('note', ['action' => 'index']);
    }
}
