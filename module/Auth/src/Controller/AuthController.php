<?php

namespace Auth\Controller;

use Auth\Form\LoginForm;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AuthController extends AbstractActionController
{
    protected $loginForm;

    protected $authService;

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

            $this->authService->getAdapter()
                ->setIdentity($request->getPost('email'))
                ->setCredential($request->getPost('password'));

            $result = $this->authService->authenticate();

            if ($result->isValid()) {
                $user = $this->authService->getAdapter()->getResultRowObject();
                $this->authService->getStorage()->write($user);

                return $this->redirect()->toRoute('home');
            } else {
                foreach ($result->getMessages() as $message) {
                    echo "$message\n";
                }
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

    public function rememberAction()
    {
        return $this->redirect()->toRoute('login');
    }
}
