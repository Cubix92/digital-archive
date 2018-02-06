<?php

namespace Generator;

use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
        ],
    ],
    'console' => [
        'router' => [
            'routes' => [
                'list-users' => [
                    'options' => [
                        'route'    => 'generate',
                        'defaults' => [
                            'controller' => Controller\IndexController::class,
                            'action'     => 'index',
                        ],
                    ],
                ],
            ],
        ],
    ],
];
