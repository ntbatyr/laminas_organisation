<?php

declare(strict_types = 1);

use Auth\Controller\Factory\LoginControllerFactory;
use Auth\Controller\LoginController;
use Auth\Controller\LogoutController;
use Laminas\Router\Http\Literal;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'login' => [
                'type' => Literal::class,
                'options' => [
                    'verb' => 'get',
                    'route' => '/login',
                    'defaults' => [
                        'controller' => LoginController::class,
                        'action' => 'index'
                    ]
                ]
            ],
            'logout' => [
                'type' => Literal::class,
                'options' => [
                    'verb' => 'get',
                    'route' => '/logout',
                    'defaults' => [
                        'controller' => LogoutController::class,
                        'action' => 'index'
                    ]
                ]
            ],
        ]
    ],

    'controllers' => [
        'factories' => [
            LoginController::class => LoginControllerFactory::class,
            LogoutController::class => InvokableFactory::class
        ]
    ],

    'view_manager' => [
        'template_path_stack' => [
            'album' => __DIR__ . '/../view',
        ],
    ],

    'translator' => [
        // 'locale' => 'ru_RU',
        'translation_file_patterns' => [
            [
                'type'     => 'phparray',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.php',
            ],
        ],
    ],
];
