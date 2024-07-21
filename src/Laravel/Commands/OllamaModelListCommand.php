<?php

declare(strict_types=1);

namespace Hanwoolderink\Ollama\Laravel\Commands;

use DateTime;
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
                Str::of($model->digest)->substr(0, 10),
                $model->sizeReadable(),
                $model->modified_at->diff(new DateTime())->format('%a minutes ago'),
            ];
        });

        $this->table(['NAME', 'ID', 'SIZE', 'MODIFIED'], $rows);
    }
}
