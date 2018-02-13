<?php

namespace Generator\Controller;

use Generator\Module;
use Zend\Mvc\Console\Controller\AbstractConsoleController;

class ListenerController extends AbstractConsoleController
{
    public function listenerAction()
    {
        $moduleName = $this->getRequest()->getParam('module', 'Application');
        $listenerName = $this->getRequest()->getParam('name');

        $filteredModuleName = ucfirst(strtolower(trim($moduleName)));

        if (!file_exists(Module::MODULES_PATH . $filteredModuleName)) {
            $this->getConsole()->writeLine("Moduł o nazwie $filteredModuleName nie istnieje!");
            exit;
        };

        var_dump($listenerName, $moduleName);die;
    }
}
