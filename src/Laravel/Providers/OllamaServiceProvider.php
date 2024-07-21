<?php

declare(strict_types=1);

namespace Hanwoolderink\Ollama\Laravel\Providers;

use GuzzleHttp\Client;
use Hanwoolderink\Ollama\Laravel\Commands\OllamaAskCommand;
use Hanwoolderink\Ollama\Laravel\Commands\OllamaModelListCommand;
use Hanwoolderink\Ollama\Ollama;
use Illuminate\Support\ServiceProvider;

class OllamaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../ollamaConfig.php', 'ollama');

        if(function_exists('config_path')) {
            $this->publishes([
                __DIR__ . '/../ollamaConfig.php' => config_path('ollama.php'),
            ], 'config');
        }

        $this->commands([
            OllamaModelListCommand::class,
            OllamaAskCommand::class,
        ]);

        $this->app->bind(Ollama::class, function () {
            if(function_exists('config')) {
                $config = config('ollama.client_config');
                $client = new Client($config);

                return new Ollama($client);
            }

            return new Ollama();
        });
    }
}
