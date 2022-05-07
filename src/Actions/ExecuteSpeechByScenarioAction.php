<?php


namespace Decole\Quasar\Actions;


use Decole\Quasar\Dto\ConfigDto;
use Decole\Quasar\Exception\ApiException;
use Decole\Quasar\Http\Client\ApiClient;

class ExecuteSpeechByScenarioAction extends AbstractAction
{
    protected $url = 'https://iot.quasar.yandex.ru/m/user/scenarios/%s/actions';

    public function __construct(ConfigDto $config)
    {
        $this->url = sprintf($this->url, $config->scenarioId);

        parent::__construct($config);
    }

    /**
     * @throws ApiException
     */
    public function execute(): bool
    {
        $data = $this->client->request($this->url, ApiClient::POST, []);

        $this->validate($data);

        return $data['status'] == 'ok';
    }
}
