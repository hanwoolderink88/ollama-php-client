<?php

declare(strict_types=1);

namespace Hanwoolderink\Ollama\Laravel\Commands;

use Hanwoolderink\Ollama\Dtos\ModelList;
use Hanwoolderink\Ollama\Laravel\Facades\Ollama;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class OllamaModelListCommand extends Command
{
    protected $signature = 'ollama:model:list';

    protected $description = 'List all local models';

    public function handle(): void
    {
        $models = Ollama::model()->list();

        $rows = collect($models)->map(function (ModelList $model) {
            return [
                $model->name,
                Str::of($model->digest)->substr(0, 12),
                $model->sizeReadable(),
                $model->lastModified(),
            ];
        });

        $this->table(['NAME', 'ID', 'SIZE', 'MODIFIED'], $rows);
    }
}
