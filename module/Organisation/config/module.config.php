<?php

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Organisation\Controller\DepartmentController;
use Organisation\Controller\EmployeeController;
use Organisation\Controller\Factory\DepartmentControllerFactory;
use Organisation\Controller\Factory\EmployeeControllerFactory;

return [
    'router' => [
        'routes' => [
            'departments' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/departments',
                    'defaults' => [
                        'controller' => DepartmentController::class,
                        'action' => 'index'
                    ]
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'save' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/save',
                            'defaults' => [
                                'controller' => DepartmentController::class,
                                'action' => 'save'
                            ]
                        ]
                    ],
                    'appoint' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/appoint',
                            'defaults' => [
                                'controller' => DepartmentController::class,
                                'action' => 'appoint'
                            ]
                        ]
                    ],
                    'dismiss' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/dismiss',
                            'defaults' => [
                                'controller' => DepartmentController::class,
                                'action' => 'dismiss'
                            ]
                        ]
                    ],
                    'form' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/form[/:id]',
                            'defaults' => [
                                'controller' => DepartmentController::class,
                                'action' => 'form'
                            ],
                            'constraints' => [
                                'id' => '[1-9]\d*',
                            ]
                        ]
                    ],
                ]
            ],
            'employees' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/employees',
                    'defaults' => [
                        'controller' => EmployeeController::class,
                        'action' => 'index'
                    ]
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'form' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/form[/:id]',
                            'defaults' => [
                                'controller' => EmployeeController::class,
                                'action' => 'form'
                            ],
                            'constraints' => [
                                'id' => '[1-9]\d*',
                            ]
                        ]
                    ],
                    'save' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/save',
                            'defaults' => [
                                'controller' => EmployeeController::class,
                                'action' => 'save'
                            ]
                        ]
                    ],
                    'remove' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/remove',
                            'defaults' => [
                                'controller' => EmployeeController::class,
                                'action' => 'remove'
                            ]
                        ]
                    ]
                ]
            ],
        ]
    ],

    'controllers' => [
        'factories' => [
            DepartmentController::class => DepartmentControllerFactory::class,
            EmployeeController::class => EmployeeControllerFactory::class,
        ]
    ],

    'view_manager' => [
        'template_path_stack' => [
            'organisation' => __DIR__ . '/../view',
        ],
    ],

    'translator' => [
        'locale' => 'ru_RU',
        'translation_file_patterns' => [
            [
                'type'     => 'phparray',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.php',
            ],
        ],
    ],
];
