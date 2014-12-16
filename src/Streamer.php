<?php

namespace Mbrevda\LogStreamAdapter;

use Aura\Cli\Context;

class Streamer
{
    protected $factory;
    protected $context;
    protected $receiver;

    public function __construct(
        $factory,
        Context $context,
        Receiver $receiver
    ) {
        $this->context = $context;
        $this->factory = $factory;
        $this->recevier = $receiver;
    }
    
    public function getAddr()
    {
        $opts = $this->context->getopt(['addr::']);
        $addr = $opts->get('--addr');

        return $addr ? $addr : '127.0.0.1:33000';
    }

    public function __invoke()
    {
        $server = $this->factory->__invoke($this->receiver);
        $server->run($this->getAddr());
    }
}
