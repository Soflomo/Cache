<?php

namespace Soflomo\Cache;

return [
    'console' => [
        'router' => [
            'routes' => [
                'cache-list' => [
                    'options' => [
                        'route' => 'cache --list',
                        'defaults' => [
                            'controller' => Controller\CacheController::class,
                            'action' => 'list',
                        ],
                    ],
                ],
                'cache-status' => [
                    'options' => [
                        'route' => 'cache --status [<name>] [-h]',
                        'defaults' => [
                            'controller' => Controller\CacheController::class,
                            'action' => 'status',
                        ],
                    ],
                ],
                'cache-status-list' => [
                    'options' => [
                        'route' => 'cache --status --list [-h]',
                        'defaults' => [
                            'controller' => Controller\CacheController::class,
                            'action' => 'status-list',
                        ],
                    ],
                ],
                'cache-clear' => [
                    'options' => [
                        'route' => 'cache (--clear|--flush):mode [--force|-f] [<name>] [--expired|-e] [--by-namespace=] [--by-prefix=]',
                        'defaults' => [
                            'controller' => Controller\CacheController::class,
                            'action' => 'clear',
                        ],
                    ],
                ],
                'cache-optimize' => [
                    'options' => [
                        'route' => 'cache --optimize [<name>]',
                        'defaults' => [
                            'controller' => Controller\CacheController::class,
                            'action' => 'optimize',
                        ],
                    ],
                ],

                'fw-cache-config' => [
                    'options' => [
                        'route' => 'cache --clear-config',
                        'defaults' => [
                            'controller' => Controller\FwCacheController::class,
                            'action' => 'clear-config',
                        ],
                    ],
                ],
                'fw-cache-module' => [
                    'options' => [
                        'route' => 'cache --clear-module-map',
                        'defaults' => [
                            'controller' => Controller\FwCacheController::class,
                            'action' => 'clear-module-map',
                        ],
                    ],
                ],
                'opcode-cache-clear' => [
                    'options' => [
                        'route' => 'cache --clear-opcode',
                        'defaults' => [
                            'controller' => Controller\OpcodeCacheController::class,
                            'action' => 'clear',
                        ],
                    ],
                ],

                'doctrine-cache-flush' => [
                    'options' => [
                        'route' => 'cache --clear-doctrine [--query|-q] [--result|-r] [--metadata|-m] [--hydration|-h]',
                        'defaults' => [
                            'controller' => Controller\DoctrineCacheController::class,
                            'action' => 'flush',
                        ],
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\CacheController::class => Controller\CacheControllerFactory::class,
            Controller\FwCacheController::class => Controller\FwCacheControllerFactory::class,
            Controller\OpcodeCacheController::class => Controller\OpcodeCacheController::class,
            Controller\DoctrineCacheController::class => Controller\DoctrineCacheController::class,
        ],
    ],
];
