<?php

namespace Mbrevda\CliLogStreamer;

use Mbrevda\Monolog\LogStream as LogStreamService;
use Aura\Cli\Context;
use Aura\Cli\Stdio;

class LogStreamer
{
    public function __construct(
        ServiceFactory $factory,
        Context $context,
        Stdio $stdio
    ) {
        $this->context = $context;
        $this->stdio = $stdio;
        $this->factory = $factory;
    }
    
    public function logCallback($msg)
    {
        $this->stdio->outln($msg);
    }

    public function getAddr()
    {
        $opts = $this->context->get('addr::');
        $addr = $opts->get('--addr');

        return $addr ? $addr : '127.0.0.1:33000';
    }

    public function __invoke()
    {
        $logServer = $this->factory([$this, 'callback']);
        $logServer->run($this->getAddr());
    }
}
