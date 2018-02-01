<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

use Zend\Session\Storage\SessionArrayStorage;
use Zend\Session\Validator\RemoteAddr;
use Zend\Session\Validator\HttpUserAgent;

return [
    'session_config' => [
        'cookie_lifetime' => 3600,
        'gc_maxlifetime'     => 3600*24*30,
    ],
    'session_manager' => [
        'validators' => [
            RemoteAddr::class,
            HttpUserAgent::class,
        ]
    ],
    'session_storage' => [
        'type' => SessionArrayStorage::class
    ],
    'navigation' => [
        'default' => [
            [
                'label' => 'Użytkownicy',
                'route' => 'user',
                'resource'=> 'user',
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
                'resource'=> 'category',
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
                'resource'=> 'note',
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
                'resource' => 'tag',
                'pages' => [
                    [
                        'label'  => 'Podgląd tagu',
                        'route'  => 'tag',
                        'action' => 'show',
                    ]
                ],
            ],
            [
                'label' => 'Logi',
                'route' => 'log',
                'resource' => 'log',
                'pages' => [
                    [
                        'label'  => 'Przeglądanie logów',
                        'route'  => 'log',
                        'action' => 'index',
                    ]
                ],
            ],
        ],
    ],
    'acl' =>[
        'admin'=> [
            'user',
            'category',
            'note',
            'tag',
            'log',
        ],
        'moderator' => [
            'category',
            'note',
            'tag',
            'log',
        ],
        'editor' => [
            'category',
            'note',
            'tag'
        ],
        'author' => [
            'note'
        ],
        'subscriber'=> [
            'home'
        ]
    ]
];
