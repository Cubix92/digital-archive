<?php

namespace Auth\Service;

use Zend\Authentication\AuthenticationService as AuthService;

class LoginService
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function authenticate($identity, $password)
    {
        $this->authService->getAdapter()
            ->setIdentity($identity)
            ->setCredential($password);

        $result = $this->authService->authenticate();

        if ($result->isValid()) {
            $user = $this->authService->getAdapter()->getResultRowObject();
            $this->authService->getStorage()->write($user);

            return true;
        }

        return false;
    }

    public function clear()
    {
        $this->authService->clearIdentity();
    }
}