<?php

namespace Decole\Quasar\Actions;

interface ActionInterface
{
    public function execute();

    public function createContext(array $data);
}
