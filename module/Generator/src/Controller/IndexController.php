<?php

namespace Generator\Controller;

use Zend\Mvc\Console\Controller\AbstractConsoleController;

class IndexController extends AbstractConsoleController
{
    public function indexAction()
    {
        $this->getConsole()->writeLine('Would you like to generate?');
        $this->getConsole()->writeLine('[C] Controller');
        $this->getConsole()->writeLine('[L] Listener');
        $this->getConsole()->writeLine('[E] Entity');
        $this->getConsole()->writeLine('[F] Form');
    }
}
