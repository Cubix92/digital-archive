<?php

namespace Auth\Controller;

use Auth\Form\UserForm;
use Auth\Model\UserTable;
use Auth\Model\User;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UserController extends AbstractActionController
{
    protected $userTable;

    protected $userForm;

    public function __construct(UserTable $userTable, UserForm $userForm)
    {
        $this->userTable = $userTable;
        $this->userForm = $userForm;
    }

    public function indexAction()
    {
        return new ViewModel([
            'users' => $this->userTable->findAll()
        ]);
    }

    public function addAction()
    {
        $request = $this->getRequest();
        $form = $this->userForm;

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($this->userForm->isValid()) {
                /** @var User $user */
                $user = $form->getData();
                $this->userTable->save($user);
                $this->flashMessenger()->addSuccessMessage('User was added successfull');
                return $this->redirect()->toRoute('user');
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
            /** @var User $user */
            $user = $this->userTable->findById($id);
        } catch (\InvalidArgumentException $e) {
            $this->flashMessenger()->addErrorMessage($e);
            return $this->redirect()->toRoute('user', ['action' => 'index']);
        }

        $this->userForm->getInputFilter()->get('password')->setRequired(false);
        $form = $this->userForm->bind($user);
        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->userTable->save($user);
                $this->flashMessenger()->addSuccessMessage('User was updated successfull.');
                return $this->redirect()->toRoute('user', ['action' => 'index']);
            }
        }

        return new ViewModel([
            'user' => $user,
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
            /** @var User $user */
            $user = $this->userTable->findById($id);
        } catch (\InvalidArgumentException $e) {
            $this->flashMessenger()->addSuccessMessage($e->getMessage());
            return $this->redirect()->toRoute('user', ['action' => 'index']);
        }

        $this->userTable->delete($user->getId());
        $this->flashMessenger()->addSuccessMessage('User was deleted successful.');
        return $this->redirect()->toRoute('user', ['action' => 'index']);
    }
}
