<?php

namespace UserTest\Controller;

use Auth\Form\LoginForm;
use Zend\Form\FormElementManager;
use Zend\Stdlib\ArrayUtils;

class AuthControllerTest extends AbstractControllerTest
{
    protected $traceError = true;

    public function setUp()
    {
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));

        parent::setUp();
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
            ->get(FormElementManager::class)->get(LoginForm::class);

        $postData = [
            'email'  => 'example@example.com',
            'role' => 'example1234',
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
