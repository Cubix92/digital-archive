<?php

namespace Generator\Controller;

use Generator\Module;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\FileGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Console\ColorInterface;
use Zend\Json\Json;
use Zend\Mvc\Console\Controller\AbstractConsoleController;

class ModuleController extends AbstractConsoleController
{
    public function generateAction()
    {
        $moduleName = $this->getRequest()->getParam('name');
        $filteredModuleName = ucfirst(strtolower(trim($moduleName)));

        if (file_exists(Module::MODULES_PATH . $filteredModuleName)) {
            $this->getConsole()->writeLine("Moduł o nazwie $filteredModuleName już istnieje.");
            exit;
        };

        $this->createConfig($filteredModuleName);
        $this->createModule($filteredModuleName);
        $this->updateConfiguration($filteredModuleName);
        $this->updateComposerJson($filteredModuleName);

        $this->getConsole()->writeLine("Udało się wygenerować nowy moduł!", ColorInterface::BLACK, ColorInterface::GREEN);
        $this->getConsole()->writeLine("Nie zapomnij wykonać komendy `composer dump-autoload`", ColorInterface::LIGHT_RED);
    }

    protected function createConfig(string $filteredModuleName)
    {
        $configPath = Module::MODULES_PATH . $filteredModuleName . '/config';

        mkdir($configPath, 0777, true);

        $moduleConfig = (new FileGenerator())
            ->setNamespace($filteredModuleName)
            ->setBody('return [];');

        file_put_contents($configPath . '/module.config.php', $moduleConfig->generate());
    }

    protected function createModule(string $filteredModuleName)
    {
        $srcPath = Module::MODULES_PATH . $filteredModuleName . '/src';

        mkdir($srcPath, 0777, true);

        $moduleMethods = [];

        $moduleMethods[] = (new MethodGenerator())
            ->setName('getConfig')
            ->setBody("return include __DIR__ . '/../config/module.config.php';");

        $moduleClass = (new ClassGenerator())
            ->setName('Module')
            ->addMethods($moduleMethods);

        $module = (new FileGenerator())
            ->setNamespace($filteredModuleName)
            ->setClass($moduleClass);

        file_put_contents($srcPath . '/Module.php', $module->generate());
    }

    protected function updateConfiguration(string $filteredModuleName)
    {
        $modulesConfig = './config/modules.config.php';
        $modules = include($modulesConfig);
        $modules[] = $filteredModuleName;

        $modulesText = PHP_EOL;

        foreach ($modules as $module) {
            $modulesText .= str_repeat(' ', 4) . "'$module'," . PHP_EOL;
        }

        $modulesList = (new FileGenerator())
            ->setBody("return [$modulesText];");

        file_put_contents($modulesConfig, $modulesList->generate());
    }

    protected function updateComposerJson(string $filteredModuleName)
    {
        $composerPath = './composer.json';
        $composerJson = file_get_contents($composerPath);
        $jsonArray = JSON::decode($composerJson, JSON::TYPE_ARRAY);
        $jsonArray['autoload']['psr-4'][$filteredModuleName . '\\'] = "module/$filteredModuleName/src/";

        file_put_contents($composerPath, json_encode($jsonArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
