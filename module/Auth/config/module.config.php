<?php

namespace Auth;

use Zend\Permissions\Acl\Acl;
use Zend\Router\Http\Segment;
use Zend\Router\Http\Literal;
use Zend\Authentication\AuthenticationService as AuthService;
use Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter as AuthAdapter;

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
            AuthService::class => Factory\AuthServiceFactory::class,
            AuthAdapter::class => Factory\AuthAdapterFactory::class,
            Listener\AuthListener::class => Factory\AuthListenerFactory::class,
            Listener\UserListener::class => Factory\UserListenerFactory::class,
            Acl::class => Factory\AclFactory::class
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
            Form\UserInputFilter::class => Factory\UserInputFilterFactory::class,
            Form\LoginInputFilter::class => Factory\LoginInputFilterFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];