<?php


namespace Decole\Quasar\Dto;


class DeviceDto
{
    private string $id;

    private string $name;

    private string $type;

    private string $itemType;

    private array $properties;

    public function __construct(array $device)
    {
        $this->id = $device['id'];
        $this->name = $device['name'];
        $this->type = $device['type'];
        $this->itemType = $device['item_type'];
        $this->properties = $device;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getItemType(): string
    {
        return $this->itemType;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }
}
