<?php


namespace Decole\Quasar\Actions;


use Decole\Quasar\Dto\ConfigDto;
use Decole\Quasar\Exception\ApiException;
use Decole\Quasar\Http\Client\ApiClient;

class DeleteScenarioAction extends AbstractAction
{
    protected $url = 'https://iot.quasar.yandex.ru/m/user/scenarios/%s';

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
        $data = $this->client->request($this->url, ApiClient::DELETE, []);

        $this->validate($data);

        return $data['status'] === 'ok';
    }
}
