<?php

declare(strict_types=1);

namespace Hanwoolderink\Ollama\Laravel\Commands;

use Hanwoolderink\Ollama\Laravel\Facades\Ollama;
use Illuminate\Console\Command;

class OllamaModelPullCommand extends Command
{
    protected $signature = 'ollama:model:pull {model}';

    protected $description = 'Pull a model from the server';

    public function handle(): void
    {
        /** @var string $model */
        $model = $this->argument('model');


        $this->info('Pulling model...');

        // Pull model here
        Ollama::model()->pull($model);

        $this->info('Model pulled');
    }
}
