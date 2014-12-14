<?php

namespace Mbrevda\LogStreamer\_Config;

use Aura\Di\Config;
use Aura\Di\Container;

class Common extends Config
{
    public function define(Container $di)
    {
        $di->params['Mbrevda\LogStreamer']['ServiceFactory']
            = $di->newFactory('Mbrevda\Monolog\Logstreamer');
    }

    public function modify(Container $di)
    {
        $dispatcher = $di->get('aura/cli-kernel:dispatcher');
        $dispatcher->addObject('logger:view', 'Mbrevda\LogStreamer');

        $help_service = $di->get('aura/cli-kernel:help_service');
        $help = $di->newInstance('Aura\Cli\Help');
        $help_service->set('logger:view', function () use ($help) {
            $help->setUsage('logger:view');
            $help->setSummary('Streams entiries to the Streaming Logger Service');
            $this->setOptions([
                'addr::' => 'The address to the Streaming Logger Service'
            ]);
            return $help;

        });
    }
}
