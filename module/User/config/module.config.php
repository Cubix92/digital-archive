<?php

namespace User;

use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'user' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/user[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\UserController::class => Factory\UserControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Model\UserTable::class => Factory\UserTableFactory::class,
            Model\UserHydrator::class => Factory\UserHydratorFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            Form\UserForm::class => Factory\UserFormFactory::class,
        ],
    ],

    'input_filters' => [
        'factories' => [
            Form\UserInputFilter::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];