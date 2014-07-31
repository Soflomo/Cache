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

use Zend\Console\ColorInterface as Color;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\ModuleManager\Listener\ListenerOptions;

class FwCacheController extends AbstractActionController
{
    public function clearConfigAction()
    {
        $console = $this->getConsole();
        $options = $this->getOptions();

        if (!$options->getConfigCacheEnabled()) {
            $console->writeLine('Config cache is not enabled, will not clear config cache', Color::RED);
            return;
        }

        $file = $options->getConfigCacheFile();
        if (!is_file($file) || !is_writable($file)) {
            $console->writeLine(sprintf('File %s cannot be found or is not writable', $file), Color::WHITE, Color::RED);
            return;
        }

        unlink($file);
        $console->writeLine(sprintf(
            'Configuration file %s cleared', $file
        ));
    }

    public function clearModuleMapAction()
    {
        $console = $this->getConsole();
        $options = $this->getOptions();

        if (!$options->getModuleMapCacheEnabled()) {
            $console->writeLine('Module map is not enabled, will not clear module map', Color::RED);
            return;
        }

        $file = $options->getModuleMapCacheFile();
        if (!is_file($file) || !is_writable($file)) {
            $console->writeLine(sprintf('File %s cannot be found or is not writable', $file), Color::WHITE, Color::RED);
            return;
        }

        unlink($file);
        $console->writeLine(sprintf(
            'Module map file %s cleared', $file
        ));
    }

    protected function getConsole()
    {
        return $this->getServiceLocator()->get('console');
    }

    protected function getOptions()
    {
        $serviceLocator  = $this->getServiceLocator();
        $configuration   = $serviceLocator->get('ApplicationConfig');
        $listenerOptions = new ListenerOptions($configuration['module_listener_options']);

        return $listenerOptions;
    }
}
