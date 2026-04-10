<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Laravel;

use Budgetlens\Intrapost\IntrapostClient;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\ServiceProvider;

class IntrapostServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/intrapost.php', 'intrapost');

        $this->app->singleton(IntrapostClient::class, function ($app) {
            $config = $app['config']['intrapost'];

            return new IntrapostClient(
                apiKey: $config['api_key'],
                accountNumber: $config['account_number'],
                baseUrl: $config['base_url'],
                httpClient: new GuzzleClient([
                    'base_uri' => $config['base_url'],
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ],
                    'timeout' => $config['timeout'],
                ]),
            );
        });

        $this->app->alias(IntrapostClient::class, 'intrapost');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../Config/intrapost.php' => config_path('intrapost.php'),
            ], 'intrapost-config');
        }
    }
}
