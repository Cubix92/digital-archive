<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\I18n\Translator\Loader\PhpArray;
use Zend\I18n\Translator\Resources;
use Zend\Mvc\I18n\Translator;
use Zend\Mvc\MvcEvent;

class Module
{
    const VERSION = '3.0.3-dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        $viewModel = $e->getApplication()->getMvcEvent()->getViewModel();
        $viewModel->isHomePage = $e->getRequest()->getUri()->getPath() == '/';

        $sm = $e->getApplication()->getServiceManager();
        $translator = $sm->get('MvcTranslator');

        /**
         * @var Translator $translator
         */
//        $translator->addTranslationFile(PhpArray::class, __DIR__ . '/../../../vendor/zendframework/zend-i18n-resources/languages/pl/Zend_Validate.php');

    }
}
