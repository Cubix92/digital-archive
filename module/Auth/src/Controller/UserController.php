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
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            $this->flashMessenger()->addErrorMessage('Identifier not found');
            return $this->redirect()->toRoute('user', ['action' => 'add']);
        }

        try {
            $user = $this->userTable->findById($id);
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($e);
            return $this->redirect()->toRoute('user', ['action' => 'index']);
        }

        $form = $this->userForm;
        $form->bind($user);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->userTable->save($user);
                $this->flashMessenger()->addSuccessMessage('User was updated successfull');
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
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            $this->flashMessenger()->addErrorMessage(sprintf('User with identifier "%s" not found', $id));
            return $this->redirect()->toRoute('user');
        }

        $this->userTable->delete($id);
        $this->flashMessenger()->addSuccessMessage('User was deleted successful');
        return $this->redirect()->toRoute('user', ['action' => 'index']);
    }
}
