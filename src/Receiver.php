<?php

namespace Mbrevda\LogStreamAdapter;

use Aura\Cli\Context;
use Aura\Cli\Stdio;

class Receiver
{
    protected $context;
    protected $stdio;

    public function __construct(
        Context $context,
        Stdio $stdio
    ) {
        $this->stdio = $stdio;
        $this->context = $context;
    }


    public function __invoke($msg)
    {
        $msg = $this->formatMessage($msg);
        $this->stdio->outln($msg);
    }

    /**
     * Outputs a message
     *
     * @param string $msg the mreceived message
     */
    private function formatMessage($msg)
    {
        //print_r($msg);
        $out = PHP_EOL . PHP_EOL
            . '<<bold>>['
            . $msg->datetime->date
            . '] '
            . str_pad($msg->level_name, 8)
            . ' '
            . ($msg->channel ? $msg->channel . ' ' : '')
            . ($msg->extra->class ? $msg->extra->class . '::' : '')
            . $msg->extra->function
            . PHP_EOL
            . (is_string($msg->location) ? $msg->location : '');

        switch ($msg->level_name) {
            default:
            case 'DEBUG':
                break;
            case 'INFO':
                $out = '<<white>>' . $out;
                break;
            case 'NOTICE':
                $out = '<<green>>' . $out;
                break;
            case 'WARNING':
                $out = '<<yellow>>' . $out;
                break;
            case 'ERROR':
                $out = '<<red>>' . $out;
                break;
            case 'CRITICAL':
                $out = '<<bluebg>>' . $out;
                break;
            case 'ALERT':
                $out = '<<yellowbg>>' . $out;
                break;
            case 'EMERGENCY':
                $out = '<<redbg>>' . $out;
                break;
        }

        $out .= '<<reset>>' . PHP_EOL . PHP_EOL;

        // monolog insists on hardcoding the php error name in the error text
        // clean it out here
        if (is_string($msg->message)) {
            $out .= preg_replace('/^(E_.*?: )/', '', $msg->message);
        }

        return $out;
    }
}
