<?php

namespace Mbrevda\LogStreamAdapter\_Config;

use Aura\Di\Config;
use Aura\Di\Container;

class Common extends Config
{
    public function define(Container $di)
    {
        $di->params['Mbrevda\LogStreamAdapter\Streamer']['factory']
            = $di->newFactory('Mbrevda\LogStream\Server');
    }

    public function modify(Container $di)
    {
        $dispatcher = $di->get('aura/cli-kernel:dispatcher');
        $dispatcher->setObject(
            'logger',
            $di->newInstance('Mbrevda\LogStreamAdapter\Streamer')
        );

        $help_service = $di->get('aura/cli-kernel:help_service');
        $help = $di->newInstance('Aura\Cli\Help');
        $help_service->set('logger', function () use ($help) {
            $help->setSummary('Runs the Streaming Logger Server');
            $help->setOptions([
                'addr:' => 'The address that the server will listen on'
            ]);
            return $help;

        });
    }
}
