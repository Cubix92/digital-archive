<?php

namespace Generator\Controller;

use Zend\Mvc\Console\Controller\AbstractConsoleController;

class IndexController extends AbstractConsoleController
{
    public function indexAction()
    {
        $this->getConsole()->writeLine('test', 'red', 'yellow');
    }
}
