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
            ),
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'Soflomo\Cache\Controller\CacheController'   => 'Soflomo\Cache\Controller\CacheController',
            'Soflomo\Cache\Controller\FwCacheController' => 'Soflomo\Cache\Controller\FwCacheController',
        ),
    ),
);