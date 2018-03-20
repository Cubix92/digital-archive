<?php

namespace UserTest\Controller;

use Auth\Form\LoginForm;
use Auth\Service\LoginService;
use Prophecy\Argument;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result;
use Zend\Form\FormElementManager;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class AuthControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;

    protected $userData = [
        'email' => 'example@example.com',
        'password' => 'example1234',
        'role' => 'admin'
    ];

    public function setUp()
    {
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));

        parent::setUp();

        $authenticationService = $this->prophesize(AuthenticationService::class);
        $authenticationService->hasIdentity()->willReturn(true);
        $authenticationService->getIdentity()->willReturn((object)$this->userData);

        $loginService = $this->prophesize(LoginService::class);
        $loginService->authenticate(Argument::type('string'), Argument::type('string'))->willReturn(true);
        $loginService->clear()->willReturn(true);

        $this->getApplicationServiceLocator()->setAllowOverride(true);

        $this->getApplicationServiceLocator()->setService(LoginService::class, $loginService->reveal());
        $this->getApplicationServiceLocator()->setService(AuthenticationService::class, $authenticationService->reveal());

        $this->getApplicationServiceLocator()->setAllowOverride(false);
    }

    public function testLoginActionWithoutPost()
    {
        $this->dispatch('/login');

        $this->assertResponseStatusCode(200);
        $this->assertMatchedRouteName('login');
    }

    public function testLoginActionRedirectsAfterValidPost()
    {
        $loginForm = $this->getApplicationServiceLocator()
            ->get(FormElementManager::class)
            ->get(LoginForm::class);

        $postData = [
            'email'  => 'example@example.com',
            'password' => 'test1234',
            'csrf' => $loginForm->get('csrf')->getValue()
        ];

        $this->dispatch('/login', 'POST', $postData);

        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/');
    }

    public function testLogoutActionCanBeAccessed()
    {
        $this->dispatch('/logout');

        $this->assertResponseStatusCode(302);
        $this->assertMatchedRouteName('logout');
        $this->assertRedirectTo('/login');
    }
}
