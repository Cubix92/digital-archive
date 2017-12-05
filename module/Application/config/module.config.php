<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\I18n\Translator;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index'
                    ],
                ],
            ],
            'category' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/category[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\CategoryController::class,
                        'action'     => 'index'
                    ],
                ],
            ],
            'note' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/note[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\NoteController::class,
                        'action'     => 'index'
                    ],
                ],
            ],
            'tag' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/tag[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\TagController::class,
                        'action'     => 'index'
                    ],
                ],
            ]
        ],
    ],
    'service_manager' => [
        'factories' => [
            Model\CategoryRepository::class => Factory\CategoryRepositoryFactory::class,
            Model\CategoryCommand::class => Factory\CategoryCommandFactory::class,
            Model\NoteRepository::class => Factory\NoteRepositoryFactory::class,
            Model\NoteCommand::class => Factory\NoteCommandFactory::class,
            Model\NoteHydrator::class => Factory\NoteHydratorFactory::class,
            Model\TagRepository::class => Factory\TagRepositoryFactory::class,
            Model\TagCommand::class => Factory\TagCommandFactory::class,
            Service\TagService::class => Factory\TagServiceFactory::class,
            Listener\TagListener::class => InvokableFactory::class,
        ],
        'delegators' => [
            Translator::class => [
                Delegator\TranslatorDelegator::class
            ]
        ],
    ],
    'form_elements' => [
        'factories' => [
            Form\CategoryForm::class => Factory\CategoryFormFactory::class,
            Form\NoteForm::class => Factory\NoteFormFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\CategoryController::class => Factory\CategoryControllerFactory::class,
            Controller\NoteController::class => Factory\NoteControllerFactory::class,
            Controller\TagController::class => Factory\TagControllerFactory::class,
        ],
    ],
    'navigation' => [
        'default' => [
            [
                'label' => 'Użytkownicy',
                'route' => 'user',
                'pages' => [
                    [
                        'label'  => 'Dodawanie użytkownika',
                        'route'  => 'user',
                        'action' => 'add',
                    ],
                    [
                        'label'  => 'Edycja użytkownika',
                        'route'  => 'user',
                        'action' => 'edit',
                    ]
                ]
            ],
            [
                'label' => 'Kategorie',
                'route' => 'category',
                'pages' => [
                    [
                        'label'  => 'Dodawanie kategorii',
                        'route'  => 'category',
                        'action' => 'add',
                    ],
                    [
                        'label'  => 'Edycja kategorii',
                        'route'  => 'category',
                        'action' => 'edit',
                    ]
                ],
            ],
            [
                'label' => 'Notatki',
                'route' => 'note',
                'pages' => [
                    [
                        'label'  => 'Dodawanie notatki',
                        'route'  => 'note',
                        'action' => 'add',
                    ],
                    [
                        'label'  => 'Edycja notatki',
                        'route'  => 'note',
                        'action' => 'edit',
                    ]
                ],
            ],
            [
                'label' => 'Tagi',
                'route' => 'tag',
                'pages' => [
                    [
                        'label'  => 'Podgląd tagu',
                        'route'  => 'tag',
                        'action' => 'show',
                    ]
                ],
            ],
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/user',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'user/user/user' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/user'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
