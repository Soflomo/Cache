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

use Zend\Console\ColorInterface as Color;
use Zend\Console\Prompt\Select  as ConsoleSelect;
use Zend\Console\Prompt\Confirm as ConsoleConfirm;

class OpcodeCacheController extends AbstractActionController
{
    public function clearAction()
    {
        if (PHP_VERSION_ID < 50500) {
            $this->clearApc();
        } else {
            $this->clearOpcache();
        }
    }

    protected function clearOpcache()
    {
        $console = $this->getConsole();

        if (!(bool) ini_get('opcache.enable_cli')) {
            $console->writeLine('You must enable opcache in CLI before clearing opcode cache', Color::RED);
            $console->writeLine('Check the "opcache.enable_cli" setting in your php.ini (see http://www.php.net/opcache.configuration)');
            return;
        }

        $scripts = opcache_get_status(true)['scripts'];

        if (count($scripts) === 0) {
            $console->writeLine('No files cached in OPcache, aborting', Color::RED);
            return;
        }

        foreach (array_keys($scripts) as $file) {
            $result = opcache_invalidate($file, true);
            if (!$result) {
                $console->writeLine('Failed to clear opcode cache for ' . $file, Color::RED);
            }
        }

        $console->writeLine(sprintf('%s OPcache files cleared', count($scripts)), Color::GREEN);
    }

    protected function clearApc()
    {
        $console = $this->getConsole();

        if (!(bool) ini_get('apc.enable_cli')) {
            $console->writeLine('You must enable APC in CLI before clearing APC cache', Color::RED);
            $console->writeLine('Check the "apc.enable_cli" setting in your php.ini (see http://www.php.net/apc.configuration)');
            return;
        }

        $info    = array_keys(apc_cache_info());
        $scripts = $info['cache_list'];

        if (count($scripts) === 0) {
            $console->writeLine('No files cached in APC, aborting', Color::RED);
            return;
        }

        array_map(function($value){
            return $value['filename'];
        }, $scripts);

        $results = apc_delete_file($scripts);

        foreach ($results as $result) {
            $console->writeLine('Failed to clear opcode cache for ' . $result, Color::RED);
        }

        $console->writeLine(sprintf('%s APC files cleared', count($scripts)), Color::GREEN);
    }

    protected function getConsole()
    {
        return $this->getServiceLocator()->get('console');
    }
}
