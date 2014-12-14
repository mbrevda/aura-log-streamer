<?php

namespace Mbrevda\LogStreamAdapter;

use Aura\Cli\Context;
use Aura\Cli\Stdio;

class Streamer
{
    public function __construct(
        $factory,
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
        $server = $this->factory([$this, 'callback']);
        $server->run($this->getAddr());
    }
}
