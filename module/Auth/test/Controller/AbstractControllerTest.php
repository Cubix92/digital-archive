<?php

namespace UserTest\Controller;

use Prophecy\Argument;
use Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter as AuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result;
use Zend\Authentication\Storage\Session;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

abstract class AbstractControllerTest extends AbstractHttpControllerTestCase
{
    protected $userData = [
        'email' => 'example@example.com',
        'password' => 'example1234',
        'role' => 'admin'
    ];

    public function setUp()
    {
        parent::setUp();

        $this->getApplicationServiceLocator()->setAllowOverride(true);

        $this->getApplicationServiceLocator()
            ->setService('config', $this->fetchConfig());

        $this->getApplicationServiceLocator()
            ->setService(AuthenticationService::class, $this->mockAuthenticationService()->reveal());

        $this->getApplicationServiceLocator()->setAllowOverride(false);
    }

    protected function fetchConfig()
    {
        $config = $this->getApplicationServiceLocator()->get('config');
        $config['db'] = [];

        return $config;
    }

    protected function mockAuthenticationService()
    {
        $result = new Result(1, $this->userData['email']);
        $authAdapter = $this->getApplication()->getServiceManager()->get(AuthAdapter::class);

        $authenticationService = $this->prophesize(AuthenticationService::class);
        $authenticationService->hasIdentity()->willReturn(true);
        $authenticationService->getIdentity()->willReturn((object)$this->userData);
        $authenticationService->clearIdentity()->willReturn(null);
        $authenticationService->authenticate(Argument::any())->willReturn($result);
        $authenticationService->getAdapter()->willReturn($authAdapter);
        $authenticationService->getStorage()->willReturn(new Session());

        return $authenticationService;
    }
}
