<?php


namespace Decole\Quasar\Actions;


use Decole\Quasar\Dto\DeviceDto;

class DevicesAction extends AbstractAction
{
    protected $url = 'https://iot.quasar.yandex.ru/m/v3/user/devices';

    public function createContext(array $data): array
    {
        $devices = [];

        foreach ($data['households'] as $household) {
            foreach ($household['all'] as $device) {
                $devices[] = $this->getDeviceDto($device);
            }
        }

        return $devices;
    }

    private function getDeviceDto(array $device): DeviceDto
    {
        return (new DeviceDto($device));
    }
}
