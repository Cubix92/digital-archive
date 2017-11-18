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

    protected $userForm;

    public function __construct(UserTable $userTable, UserForm $userForm)
    {
        $this->userTable = $userTable;
        $this->userForm = $userForm;
    }

    public function indexAction()
    {
        return new ViewModel([
            'users' => $this->userTable->fetchAll()
        ]);
    }

    public function addAction()
    {
        $request = $this->getRequest();
        $form = $this->userForm;

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($this->userForm->isValid()) {
                $user = new User();
                $user->exchangeArray($form->getData());
                $this->userTable->save($user);

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
            return $this->redirect()->toRoute('user', ['action' => 'add']);
        }

        try {
            $user = $this->userTable->getUser($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('user', ['action' => 'index']);
        }

        $form = $this->userForm;
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
