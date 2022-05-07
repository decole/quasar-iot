<?php


namespace Decole\Quasar\Adapters\Laravel\Facades;


use Decole\Quasar\QuasarClient;
use Illuminate\Support\Facades\Facade;

class QuasarFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return QuasarClient::class;
    }
}
