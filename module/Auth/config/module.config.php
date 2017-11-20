<?php

namespace Auth;

use Zend\Router\Http\Segment;
use Zend\Router\Http\Literal;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Authentication\AuthenticationService;

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
            'login' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/login',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'login',
                    ],
                ],
            ],
            'logout' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/logout',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'logout',
                    ],
                ],
            ],
            'remember' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/remember',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'remember',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\UserController::class => Factory\UserControllerFactory::class,
            Controller\AuthController::class => Factory\AuthControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Model\UserTable::class => Factory\UserTableFactory::class,
            Model\UserHydrator::class => InvokableFactory::class,
            AuthenticationService::class => Factory\AuthServiceFactory::class
        ],
    ],
    'form_elements' => [
        'factories' => [
            Form\UserForm::class => Factory\UserFormFactory::class,
            Form\LoginForm::class => Factory\LoginFormFactory::class,
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