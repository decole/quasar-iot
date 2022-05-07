<?php


namespace Decole\Quasar\Actions;


use Decole\Quasar\Dto\ConfigDto;
use Decole\Quasar\Exception\ApiException;
use Decole\Quasar\Http\Client\ApiClient;

class ChangeTextSpeechByScenarioAction extends AbstractAction
{
    protected $url = 'https://iot.quasar.yandex.ru/m/v3/user/scenarios/';

    private string $text;

    public function __construct(ConfigDto $config, string $text)
    {
        $this->url .= $config->scenarioId;
        $this->text = $text;

        parent::__construct($config);
    }

    /**
     * @throws ApiException
     */
    public function execute(): bool
    {
        $data = $this->client->request(
            $this->url,
            ApiClient::PUT,
            $this->getParams()
        );

        $this->validate($data);

        return $data['status'] === 'ok';
    }

    private function getParams(): array
    {
        return [
            'name' => 'тромб',
            'icon' => 'home',
            'triggers' => [
                [
                    'type' => 'scenario.trigger.voice',
                    'value' => $this->config->command,
                ],
            ],
            'steps' => [
                [
                    'type' => 'scenarios.steps.actions',
                    'parameters' => [
                        'launch_devices' => [
                            [
                                'id' => $this->config->deviceId,
                                'capabilities' => [
                                    [
                                        'type' => 'devices.capabilities.quasar.server_action',
                                        'state' => [
                                            'instance' => 'phrase_action',
                                            'value' => $this->text,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'requested_speaker_capabilities' => [],
                    ],
                ],
            ],
        ];
    }
}
