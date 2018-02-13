<?php

namespace Generator;

use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'controllers' => [
        'factories' => [
            Controller\ModuleController::class => InvokableFactory::class,
            Controller\ListenerController::class => InvokableFactory::class,
        ],
    ],
    'console' => [
        'router' => [
            'routes' => [
                'module' => [
                    'options' => [
                        'route'    => 'generate module --name=',
                        'defaults' => [
                            'controller' => Controller\ModuleController::class,
                            'action'     => 'generate'
                        ],
                    ],
                ],
                'listener' => [
                    'options' => [
                        'route'    => 'generate listener <name> --module=',
                        'defaults' => [
                            'controller' => Controller\ListenerController::class,
                            'action'     => 'generate'
                        ],
                    ],
                ],
            ],
        ],
    ],
];
