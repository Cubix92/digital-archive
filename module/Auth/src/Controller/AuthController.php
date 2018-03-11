<?php

namespace Auth\Controller;

use Auth\Form\LoginForm;
use Auth\Service\LoginService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AuthController extends AbstractActionController
{
    protected $loginForm;

    protected $loginService;

    public function __construct(LoginForm $loginForm, LoginService $loginService)
    {
        $this->loginForm = $loginForm;
        $this->loginService = $loginService;
    }

    public function loginAction()
    {
        $this->loginService->clear();

        $request = $this->getRequest();
        $form = $this->loginForm;

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $result = $this->loginService->authenticate(
                    $request->getPost('email'),
                    $request->getPost('password')
                );

                if ($result) {
                    $this->flashMessenger()->addSuccessMessage('You are logged successfull');
                    return $this->redirect()->toRoute('home');
                }

                $this->flashMessenger()->addErrorMessage('Your password or credentials are wrong');
                return $this->redirect()->refresh();
            }
        }

        $viewModel = new ViewModel([
            'form' => $form
        ]);

        return $viewModel->setTerminal(true);
    }

    public function logoutAction()
    {
        $this->loginService->clear();
        return $this->redirect()->toRoute('login');
    }
}
