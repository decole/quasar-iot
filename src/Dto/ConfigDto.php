<?php

namespace Decole\Quasar\Dto;

class ConfigDto
{
    public string $command;

    public string $cookie;

    public ?string $deviceId = null;

    public ?string $scenarioId = null;

    public function __construct(string $commandName, string $cookie, ?string $deviceId, ?string $scenarioId)
    {
        $this->command = $commandName;
        $this->cookie = $cookie;
        $this->deviceId = $deviceId;
        $this->scenarioId = $scenarioId;
    }
}
