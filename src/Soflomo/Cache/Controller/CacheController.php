<?php
/**
 * Copyright (c) 2013 Soflomo.
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
 * @copyright   2013 Soflomo.
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link        http://soflomo.com
 */

namespace Soflomo\Cache\Controller;

use Zend\Mvc\Controller\AbstractActionController;

use Zend\Cache\Storage\OptimizableInterface;
use Zend\Cache\Storage\AvailableSpaceCapableInterface;
use Zend\Cache\Storage\TotalSpaceCapableInterface;
use Zend\Cache\Storage\StorageInterface;

use Zend\Console\ColorInterface as Color;
use Zend\Console\Prompt\Select  as ConsoleSelect;

class CacheController extends AbstractActionController
{
    public function statusAction()
    {
        $cache   = $this->getCache();
        $console = $this->getConsole();

        $console->writeLine('Cache space:');

        if ($cache instanceof TotalSpaceCapableInterface) {
            $space = $cache->getTotalSpace();
            $unit  = 'B';

            if ($space > 1024) {
                $space = $space/1024;
                $unit  = 'KB';
            }
            if ($space > 1024) {
                $space = $space/1024;
                $unit  = 'MB';
            }
            if ($space > 1024) {
                $space = $space/1024;
                $unit  = 'GB';
            }

            $console->writeLine(sprintf(
                '%d%s total space', $space, $unit
            ));
        } else {
            $console->writeLine('Cache adapter does not provide information about the total space of this cache');
        }

        if ($cache instanceof AvailableSpaceCapableInterface) {
            $space = $cache->getAvailableSpace();
            $unit  = 'B';

            if ($space > 1024) {
                $space = $space/1024;
                $unit  = 'KB';
            }
            if ($space > 1024) {
                $space = $space/1024;
                $unit  = 'MB';
            }
            if ($space > 1024) {
                $space = $space/1024;
                $unit  = 'GB';
            }

            $console->writeLine(sprintf(
                '%d%s available', $space, $unit
            ));
        } else {
            $console->writeLine('Cache adapter does not provide information about the available space of this cache');
        }

        // Create empty line
        $console->writeLine('');
    }

    public function clearAction()
    {
        $cache = $this->getCache();
    }

    public function optimizeAction()
    {
        $cache = $this->getCache();
        if (!$cache instanceof OptimizableInterface) {
            throw new \Exception();
        }

        $result  = $cache->optimize();
        $console = $this->getConsole();

        if ($result) {
            $console->writeLine('Optimized cache successfully', Color::BLACK, Color::GREEN);
        } else {
            $console->writeLine('Could not optimize cache', Color::WHITE, Color::RED);
        }
    }

    protected function getCache()
    {
        $sl   = $this->getServiceLocator();
        $name = $this->params('name', null);

        if (null === $name) {
            // There is no name given, fetch the default from the caches array

            $config = $sl->get('Config');
            if (!array_key_exists('caches', $config)) {
                throw new \Exception('There is no cache configured');
            }

            $caches = $config['caches'];
            if (count($caches) === 1) {
                $name = key($caches);
            } elseif(count($caches) > 1) {
                $options = array_keys($caches);

                // Increase the keys by 1 since arrays are zero-based keys
                array_unshift($options, null);
                unset($options[0]);

                $answer  = ConsoleSelect::prompt(
                    'You have multiple caches defined, please select one',
                    $options
                );

                $name = $options[$answer];
            } else {
                throw new \Exception('No cache name defined, no cache is configured');
            }
        }

        $cache = $sl->get($name);
        if (!$cache instanceof StorageInterface) {
            throw new \Exception('Cache is not a cache storage');
        }

        return $cache;
    }

    protected function getConsole()
    {
        return $this->getServiceLocator()->get('console');
    }
}