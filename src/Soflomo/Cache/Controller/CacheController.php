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
    public function listAction()
    {
        $console = $this->getConsole();
        $caches  = array_keys($this->getCaches());

        foreach ($caches as $cache) {
            $console->writeLine($cache);
        }
    }

    public function statusAction()
    {
        $cache   = $this->getCache();
        $console = $this->getConsole();

        $console->writeLine('Cache space for selected cache:');
        $this->writeCacheStatus($cache);
    }

    public function statusListAction()
    {
        $console = $this->getConsole();
        $caches  = array_keys($this->getcaches());

        foreach ($caches as $name) {
            $console->writeLine('Status information for ' . $name);

            $cache = $this->getServiceLocator()->get($name);
            $this->writeCacheStatus($cache);

            $console->writeLine('');
        }
    }

    protected function writeCacheStatus(StorageInterface $cache)
    {
        $console = $this->getConsole();
        $human   = $this->params('h');

        if ($cache instanceof TotalSpaceCapableInterface) {
            $space = $cache->getTotalSpace();
            $space = $human ? $this->convertToHumanSpace($space) : $space;

            $console->writeLine(sprintf(
                '%s total space', $space
            ));
        } else {
            $console->writeLine('Cache adapter does not provide information about the total space of this cache');
        }

        if ($cache instanceof AvailableSpaceCapableInterface) {
            $space = $cache->getAvailableSpace();
            $space = $human ? $this->convertToHumanSpace($space) : $space;

            $console->writeLine(sprintf(
                '%s available', $space
            ));
        } else {
            $console->writeLine('Cache adapter does not provide information about the available space of this cache');
        }
    }

    protected function convertToHumanSpace($space)
    {
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

        return sprintf('%d%s', $space, $unit);
    }

    public function clearAction()
    {
        $cache = $this->getCache();

        $clear = $this->params('clear');
        $flush = $this->params('flush');
        if ($flush === true) {
            $force = $this->params('force') || $this->params('f');
            var_dump($flush, $force);
        } elseif ($clear === true) {
            $expired = $this->params('expired') || $this->params('e');
            $namespace = $this->params('by-namespace');
            $prefix = $this->params('by-prefix');
            var_dump($clear, $expired, $namespace, $prefix);
        }
    }

    public function optimizeAction()
    {
        $cache = $this->getCache();
        if (!$cache instanceof OptimizableInterface) {
            $type = get_class($cache);
            $type = substr($type, strrpos($type, '\\') + 1);
            throw new \Exception(sprintf(
                'The cache type %s does not support optimizing', strtolower($type)
            ));
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
        $name = $this->params('name');

        if (null === $name) {
            $name = $this->getCacheName();
        }

        $cache = $this->getServiceLocator()->get($name);
        if (!$cache instanceof StorageInterface) {
            throw new \Exception('Cache is not a cache storage');
        }

        return $cache;
    }

    protected function getCacheName()
    {
        $caches = $this->getCaches();
        if (count($caches) === 0) {
            throw new \Exception('No abstract caches registerd to select');
        }

        if (count($caches) === 1) {
            return key($caches);
        }

        $options = array_keys($caches);

        // Increase the keys by 1 since arrays are zero-based keys
        array_unshift($options, null);
        unset($options[0]);

        $answer  = ConsoleSelect::prompt(
            'You have multiple caches defined, please select one',
            $options
        );

        return $options[$answer];
    }

    protected function getCaches()
    {
        $config  = $this->getServiceLocator()->get('Config');

        if (!array_key_exists('caches', $config)) {
            throw new \Exception('No abstract caches registerd to select');
        }

        return $config['caches'];
    }

    protected function getConsole()
    {
        return $this->getServiceLocator()->get('console');
    }
}