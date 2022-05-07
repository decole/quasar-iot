<?php


namespace Decole\Quasar\Actions;


use Decole\Quasar\Http\Client\ApiClient;

class CreateScenarioAction extends AbstractAction
{
    protected $url = 'https://iot.quasar.yandex.ru/m/v3/user/scenarios/';

    public function execute(): string
    {
        $data = $this->client->request($this->url, ApiClient::POST, $this->getParams());

        $this->validate($data);

        return $data['scenario_id'];
    }

    private function getParams(): array
    {
        return [
            'name' => $this->config->command,
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
                                            'value' => $this->config->command,
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
