<?php

namespace UserTest\Controller;

use Auth\Controller\UserController;
use Auth\Form\UserForm;
use Auth\Listener\UserListener;
use Auth\Model\User;
use Auth\Model\UserTable;
use Prophecy\Argument;
use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Log\Logger;
use Zend\Form\FormElementManager;
use Zend\Mvc\Controller\AbstractActionController;

class UserControllerTest extends AuthControllerTest
{
    protected $traceError = true;

    protected $userTable;

    public function setUp()
    {
        parent::setUp();

        $this->userTable = $this->prophesize(UserTable::class);

        $sharedManager = $this->getApplication()->getEventManager()->getSharedManager();
        $sharedManager->clearListeners(AbstractActionController::class);

        $this->getApplicationServiceLocator()->setAllowOverride(true);

        $this->getApplicationServiceLocator()->setService(UserTable::class, $this->userTable->reveal());

        $this->getApplicationServiceLocator()->setAllowOverride(false);
    }

    public function testIndexActionCanBeAccessed()
    {
        $resultSet = $this->prophesize(ResultSetInterface::class)->reveal();
        $this->userTable->fetchAll()->willReturn($resultSet);

        $this->dispatch('/user', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('auth');
        $this->assertControllerName(UserController::class);
        $this->assertControllerClass('UserController');
        $this->assertMatchedRouteName('user');
    }

    public function testAddActionWithoutPost()
    {
        $this->dispatch('/user/add');

        $this->assertResponseStatusCode(200);
        $this->assertMatchedRouteName('user');
    }

    public function testAddActionRedirectsAfterValidPost()
    {
        $this->userTable
            ->save(Argument::type(User::class))
            ->shouldBeCalled();

        $userForm = $this->getApplicationServiceLocator()
            ->get(FormElementManager::class)->get(UserForm::class);

        $postData = [
            'email'  => 'example@example.com',
            'role' => 'admin',
            'password' => 'test1234',
            'repeat_password' => 'test1234',
            'csrf' => $userForm->get('csrf')->getValue()
        ];

        $this->dispatch('/user/add', 'POST', $postData);
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/user');
    }

    public function testEditActionWithoutPost()
    {
        $user = (new User())->setEmail('example@example.com');
        $this->userTable->getUser(Argument::type('integer'))->willReturn($user);

        $this->userTable
            ->getUser(Argument::type('integer'))
            ->shouldBeCalled();

        $this->dispatch('/user/edit/1');

        $this->assertResponseStatusCode(200);
        $this->assertMatchedRouteName('user');
    }

    public function testEditActionRedirectsAfterValidPost()
    {
        $user = (new User())->setEmail('example@example.com');
        $this->userTable->getUser(Argument::type('integer'))->willReturn($user);

        $this->userTable
            ->save(Argument::type(User::class))
            ->shouldBeCalled();

        $userForm = $this->getApplicationServiceLocator()
            ->get(FormElementManager::class)->get(UserForm::class);

        $postData = [
            'id' => 1,
            'email'  => 'example@example.com',
            'role' => 'admin',
            'csrf' => $userForm->get('csrf')->getValue()
        ];

        $this->dispatch('/user/edit/1', 'POST', $postData);
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/user');
    }

    public function testDeleteActionRedirectsAfterValidPost()
    {
        $user = (new User())->setId(1);

        $this->userTable->getUser(Argument::type('integer'))->willReturn($user);

        $this->userTable
            ->delete(Argument::type('integer'))
            ->shouldBeCalled();

        $this->dispatch('/user/delete/1');
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/user');
    }
}
