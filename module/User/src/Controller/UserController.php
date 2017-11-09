<?php

namespace User\Controller;

use User\Form\UserForm;
use User\Model\UserTable;
use User\Model\User;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UserController extends AbstractActionController
{
    protected $userTable;

    public function __construct(UserTable $userTable)
    {
        $this->userTable = $userTable;
    }

    public function indexAction()
    {
        return new ViewModel([
            'users' => $this->userTable->fetchAll()->buffer()
        ]);
    }

    public function addAction()
    {
        $userForm = new UserForm();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $userForm->setData($request->getPost());

            if ($userForm->isValid()) {
                $user = new User();
                $user->exchangeArray($userForm->getData());
                $this->userTable->save($user);

                return $this->redirect()->toRoute('user');
            }
        }

        return new ViewModel([
            'form' => $userForm
        ]);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('user', ['action' => 'add']);
        }

        try {
            $user = $this->userTable->getUser($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('user', ['action' => 'index']);
        }

        $form = new UserForm();
        $form->bind($user);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->userTable->save($user);
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
            return $this->redirect()->toRoute('user');
        }

        $this->userTable->delete($id);

        return $this->redirect()->toRoute('user', ['action' => 'index']);
    }
}
