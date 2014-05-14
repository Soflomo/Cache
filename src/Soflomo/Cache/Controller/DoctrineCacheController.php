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

class DoctrineCacheController extends AbstractActionController
{
    public function flushAction()
    {
        $console = $this->getConsole();
        $config  = $this->getObjectManager()->getConfiguration();

        // All is true when no other params are given
        $all = !$this->params('query')     && !$this->params('q')
            && !$this->params('result')    && !$this->params('r')
            && !$this->params('metadata')  && !$this->params('m')
            && !$this->params('hydration') && !$this->params('h');

        if ($all || $this->params('query') || $this->params('q')) {
            $config->getQueryCacheImpl()->flushAll();
            if (!$all) {
                $console->writeLine('Doctrine query cache flushed');
            }
        }
        if ($all || $this->params('result') || $this->params('r')) {
            $config->getResultCacheImpl()->flushAll();
            if (!$all) {
                $console->writeLine('Doctrine result cache flushed');
            }
        }
        if ($all || $this->params('metadata') || $this->params('m')) {
            $config->getMetadataCacheImpl()->flushAll();
            if (!$all) {
                $console->writeLine('Doctrine metadata cache flushed');
            }
        }
        if ($all || $this->params('hydration') || $this->params('h')) {
            $config->getHydrationCacheImpl()->flushAll();
            if (!$all) {
                $console->writeLine('Doctrine hydration cache flushed');
            }
        }

        if ($all) {
            $console->writeLine('All Doctrine caches are flushed');
        }
    }

    protected function getConsole()
    {
        return $this->getServiceLocator()->get('console');
    }

    protected function getObjectManager()
    {
        return $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    }
}
