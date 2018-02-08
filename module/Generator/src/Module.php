<?php

namespace Generator;

use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\Console\Adapter\AdapterInterface as Console;

class Module implements ConsoleBannerProviderInterface, ConsoleUsageProviderInterface
{
    public const MODULES_PATH = './module/';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getConsoleBanner(Console $console)
    {
        return ' --- Code Generator v0.1 ---';
    }

    public function getConsoleUsage(Console $console)
    {
        return [
            'generate module' => 'Generate modules'
        ];
    }
}
