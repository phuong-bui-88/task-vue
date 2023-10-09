<?php

namespace App\Handlers;
use Monolog\Handler\SyslogUdpHandler;

class CustomSyslogUdpHandler extends SyslogUdpHandler
{
    protected function write(array $record): void
    {
        $header = $this->makeCommonSyslogHeader($this->logLevels[$record['level']], $record['datetime']);
        $this->socket->write($record['formatted'], $header);
    }
}
