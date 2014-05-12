<?php

return array(
     'console' => array(
        'router' => array(
            'routes' => array(
                'cache-list' => array(
                    'options' => array(
                        'route'    => 'cache --list',
                        'defaults' => array(
                            'controller' => 'Soflomo\Cache\Controller\CacheController',
                            'action'     => 'list'
                        ),
                    ),
                ),
                'cache-status' => array(
                    'options' => array(
                        'route'    => 'cache --status [<name>] [-h]',
                        'defaults' => array(
                            'controller' => 'Soflomo\Cache\Controller\CacheController',
                            'action'     => 'status'
                        ),
                    ),
                ),
                'cache-status-list' => array(
                    'options' => array(
                        'route'    => 'cache --status --list [-h]',
                        'defaults' => array(
                            'controller' => 'Soflomo\Cache\Controller\CacheController',
                            'action'     => 'status-list'
                        ),
                    ),
                ),
                'cache-clear' => array(
                    'options' => array(
                        'route'    => 'cache (--clear|--flush):mode [--force|-f] [<name>] [--expired|-e] [--by-namespace=] [--by-prefix=]',
                        'defaults' => array(
                            'controller' => 'Soflomo\Cache\Controller\CacheController',
                            'action'     => 'clear'
                        ),
                    ),
                ),
                'cache-optimize' => array(
                    'options' => array(
                        'route'    => 'cache --optimize [<name>]',
                        'defaults' => array(
                            'controller' => 'Soflomo\Cache\Controller\CacheController',
                            'action'     => 'optimize'
                        ),
                    ),
                ),

                'fw-cache-config' => array(
                    'options' => array(
                        'route'    => 'cache --clear-config',
                        'defaults' => array(
                            'controller' => 'Soflomo\Cache\Controller\FwCacheController',
                            'action'     => 'clear-config'
                        ),
                    ),
                ),
                'fw-cache-module' => array(
                    'options' => array(
                        'route'    => 'cache --clear-module-map',
                        'defaults' => array(
                            'controller' => 'Soflomo\Cache\Controller\FwCacheController',
                            'action'     => 'clear-module-map'
                        ),
                    ),
                ),
                'opcode-cache-clear' => array(
                    'options' => array(
                        'route'    => 'cache --clear-opcode',
                        'defaults' => array(
                            'controller' => 'Soflomo\Cache\Controller\OpcodeCacheController',
                            'action'     => 'clear',
                        ),
                    ),
                ),

                'doctrine-cache-flush' => array(
                    'options' => array(
                        'route'    => 'cache --doctrine-flush [--query|-q] [--result|-r] [--metadata|-m] [--hydration|-h] [--all|-a]',
                        'defaults' => array(
                            'controller' => 'Soflomo\Cache\Controller\DoctrineCacheController',
                            'action'     => 'flush',
                        ),
                    ),
                ),
            ),
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'Soflomo\Cache\Controller\CacheController'         => 'Soflomo\Cache\Controller\CacheController',
            'Soflomo\Cache\Controller\FwCacheController'       => 'Soflomo\Cache\Controller\FwCacheController',
            'Soflomo\Cache\Controller\OpcodeCacheController'   => 'Soflomo\Cache\Controller\OpcodeCacheController',
            'Soflomo\Cache\Controller\DoctrineCacheController' => 'Soflomo\Cache\Controller\DoctrineCacheController',
        ),
    ),
);