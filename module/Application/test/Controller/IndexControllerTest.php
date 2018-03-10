<?php

namespace ApplicationTest\Controller;

use Zend\Authentication\AuthenticationService;
use Application\Controller\IndexController;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class IndexControllerTest extends AbstractHttpControllerTestCase
{
    public function setUp()
    {
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));

        parent::setUp();

        $config = $this->getApplicationServiceLocator()->get('config');
        unset($config['db']);

        $mockAuth = $this->getMockBuilder(AuthenticationService::class)->disableOriginalConstructor()->getMock();
        $mockAuth->expects($this->any())->method('hasIdentity')->willReturn(true);

        $this->getApplicationServiceLocator()->setAllowOverride(true);

        $this->getApplicationServiceLocator()->setService(AuthenticationService::class, $mockAuth);
        $this->getApplicationServiceLocator()->setService('config', $config);

        $this->getApplicationServiceLocator()->setAllowOverride(false);
    }

    public function _testIndexActionCanBeAccessed()
    {
        $this->dispatch('/', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('application');
        $this->assertControllerName(IndexController::class);
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('home');
    }

    public function _testIndexActionViewModelTemplateRenderedWithinLayout()
    {
        $this->dispatch('/', 'GET');
        $this->assertQuery('.container');
    }

    public function _testInvalidRouteDoesNotCrash()
    {
        $this->dispatch('/invalid/route', 'GET');
        $this->assertResponseStatusCode(404);
    }
}
