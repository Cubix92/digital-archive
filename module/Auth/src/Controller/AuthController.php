<?php

namespace Auth\Controller;

use Auth\Form\LoginForm;
use Auth\Model\User;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AuthController extends AbstractActionController
{
    protected $loginForm;

    protected $authService;

    protected $userData = [
        'email' => 'example@example.com',
        'role' => 'admin'
    ];

    public function __construct(LoginForm $loginForm, AuthenticationService $authService)
    {
        $this->loginForm = $loginForm;
        $this->authService = $authService;
    }

    public function loginAction()
    {
        $this->authService->clearIdentity();

        $request = $this->getRequest();
        $form = $this->loginForm;

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $user = $this->authService->getAdapter()->getResultRowObject();
                $this->authService->getStorage()->write($user);
                return $this->redirect()->toRoute('home');
            }
        }

        $viewModel = new ViewModel([
            'form' => $form
        ]);

        return $viewModel->setTerminal(true);
    }

    public function logoutAction()
    {
        $this->authService->clearIdentity();
        return $this->redirect()->toRoute('login');
    }
}
