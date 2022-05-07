<?php


namespace Decole\Quasar\Actions;


use Decole\Quasar\Dto\ConfigDto;
use Decole\Quasar\Exception\ApiException;
use Decole\Quasar\Http\Client\ApiClient;

abstract class AbstractAction implements ActionInterface
{
    protected ConfigDto $config;

    protected ApiClient $client;

    protected $url = 'https://iot.quasar.yandex.ru/m/v3/user/devices';

    public function __construct(ConfigDto $config)
    {
        $this->config = $config;

        $this->client = new ApiClient($config);
    }

    public function execute()
    {
        $result = [];

        $data = $this->client->request($this->url);

        $this->validate($data);

        return $this->createContext($data);
    }

    public function createContext(array $data): array
    {
        return [];
    }

    protected function validate(array $data): void
    {
        if ($data['status'] === 'ok') {
            return;
        }

        throw new ApiException('Quasar api have not status OK');
    }
}
