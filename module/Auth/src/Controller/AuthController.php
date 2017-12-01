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
                $this->authService->getAdapter()
                    ->setIdentity($request->getPost('email'))
                    ->setCredential($request->getPost('password'));

                $result = $this->authService->authenticate();

                if ($result->isValid()) {
                    $user = $this->authService->getAdapter()->getResultRowObject();
                    $this->authService->getStorage()->write($user);

                    $this->flashMessenger()->addInfoMessage(sprintf('You are logged as %s', $user->email));
                    return $this->redirect()->toRoute('home');
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
        $this->flashMessenger()->addInfoMessage(sprintf('Zostałeś wylogowany z systemu.'));
        return $this->redirect()->toRoute('login');
    }

    public function rememberAction()
    {
        return $this->redirect()->toRoute('login');
    }
}
