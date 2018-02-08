<?php

namespace Generator\Controller;

use Generator\Module;
use Zend\Mvc\Console\Controller\AbstractConsoleController;

class ListenerController extends AbstractConsoleController
{
    public function listenerAction()
    {
        $moduleName = $this->getRequest()->getParam('module');
        $filteredModuleName = ucfirst(strtolower(trim($moduleName)));

        if (file_exists(Module::MODULES_PATH . $filteredModuleName)) {
            $this->getConsole()->writeLine("Moduł o nazwie $filteredModuleName już istnieje.");
            exit;
        };
    }
}
