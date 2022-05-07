<?php


namespace Decole\Quasar;


use Decole\Quasar\Actions\DeleteScenarioAction;
use Decole\Quasar\Actions\DevicesAction;
use Decole\Quasar\Actions\ChangeTextSpeechByScenarioAction;
use Decole\Quasar\Actions\CreateScenarioAction;
use Decole\Quasar\Actions\ExecuteSpeechByScenarioAction;
use Decole\Quasar\Dto\ConfigDto;
use Decole\Quasar\Dto\DeviceDto;
use Decole\Quasar\Exception\ApiException;
use Decole\Quasar\Exception\RussianWordException;

class QuasarClient
{
    private ConfigDto $config;

    /**
     * @throws RussianWordException
     */
    public function __construct(
        string $cookies,
        string $command = 'Голос',
        ?string $deviceId = null,
        ?string $scenarioId = null
    ) {
        $this->isRussian($command);

        $this->config = new ConfigDto($command, $cookies, $deviceId, $scenarioId);
    }

    public function setDeviceId(string $deviceId): void
    {
        $this->config->deviceId = $deviceId;
    }

    public function setScenarioId(string $scenarioId): void
    {
        $this->config->scenarioId = $scenarioId;
    }

    /**
     * @return array<int, DeviceDto>
     */
    public function getDevices(string $type = 'all'): array
    {
        $devices = (new DevicesAction($this->config))->execute();

        if ($type === 'all') {
            return $devices;
        }

        $result = [];

        foreach ($devices as $device) {
            if ($device->getItemType() === $type || $device->getType() === $type) {
                $result[] = $device;
            }
        }

        return $result;
    }

    /**
     * @return string scenario_id
     *
     * @throws RussianWordException
     */
    public function createScenario(): string
    {
        $this->isRussian($this->config->command);

        return (new CreateScenarioAction($this->config))->execute();
    }

    /**
     * @throws RussianWordException|ApiException
     */
    public function changeTextSpeechByScenario(string $text): bool
    {
        $this->isNormalCountSymbols($text);
        $this->isRussian($text);

        return (new ChangeTextSpeechByScenarioAction($this->config, $text))->execute();
    }

    /**
     * @throws ApiException
     */
    public function executeSpeechByScenario(): bool
    {
        return (new ExecuteSpeechByScenarioAction($this->config))->execute();
    }

    /**
     * @throws ApiException
     */
    public function deleteScenario(): bool
    {
        return (new DeleteScenarioAction($this->config))->execute();
    }

    /**
     * @throws RussianWordException
     */
    private function isRussian(string $text): void
    {
        if (!preg_match('/[А-Яа-яЁё]/u', $text)) {
            throw new RussianWordException();
        }
    }

    /**
     * @throws ApiException
     */
    private function isNormalCountSymbols(string $text): void
    {
        if (mb_strlen($text) > 100) {
            throw new ApiException('Max message 100 symbols');
        }
    }
}
