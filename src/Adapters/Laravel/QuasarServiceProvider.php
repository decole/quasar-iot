<?php


namespace Decole\Quasar\Adapters\Laravel;


use Decole\Quasar\QuasarClient;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class QuasarServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function boot(): void
    {
        $this->publishes(
            [
                __DIR__ . '/config/quasariot.php' => config_path('quasariot.php'),
            ],
            'config',
        );
    }

    public function register(): void
    {
        $this->app->singleton(QuasarClient::class, function () {
            return new QuasarClient(
                config('quasariot.cookies'),
                config('quasariot.commandName'),
                config('quasariot.deviceId'),
                config('quasariot.scenarioId')
            );
        });
    }

    public function provides(): array
    {
        return [QuasarClient::class];
    }
}
