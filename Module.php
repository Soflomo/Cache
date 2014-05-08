<?php
/**
 * Copyright (c) 2013-2014 Soflomo.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of the
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @author      Jurian Sluiman <jurian@soflomo.com>
 * @copyright   2013-2014 Soflomo.
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link        http://soflomo.com
 */

namespace Soflomo\Cache;

use Zend\Loader;
use Zend\ModuleManager\Feature;
use Zend\Console\Adapter\AdapterInterface as Console;

class Module implements
    Feature\ConfigProviderInterface,
    Feature\AutoloaderProviderInterface,
    Feature\ConsoleUsageProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            Loader\AutoloaderFactory::STANDARD_AUTOLOADER => array(
                Loader\StandardAutoloader::LOAD_NS => array(
                    __NAMESPACE__ => __DIR__ . '/src/Soflomo/Cache',
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getConsoleUsage(Console $console)
    {
        return array(
            'Clear',
            'cache --flush [--force|-f] [<name>]'                 => 'Flush complete cache',
            'cache --clear [--force|-f] [<name>] --expired|-e'    => 'Clear expired cache',
            'cache --clear [--force|-f] [<name>] --by-namespace=' => 'Clear cache by namespace',
            'cache --clear [--force|-f] [<name>] --by-prefix='    => 'Clear cache by prefix',

            array('<name>',          'Name of the cache; in case there is one cache it can be left blank'),
            array('--expired|-e',    'Clear all expired items in cache'),
            array('--by-namespace=', 'Clear all items in cache with given namespace'),
            array('--by-prefix=',    'Clear all items in cache with given prefix'),
            array('--force|-f',      'Force clearing, without asking confirmation'),

            'Optimize',
            'cache --optimize [<name>]'                           => 'Optimize cache',

            array('<name>',          'Name of the cache; in case there is one cache it can be left blank'),

            'Status information',
            'cache --status [<name>] [-h]'                        => 'Show (storage) information about the cache',

            array('<name>',          'Name of the cache; in case there is one cache it can be left blank'),
            array('-h',              'Show status in human readable output'),
        );
    }
}