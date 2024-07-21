<?php

declare(strict_types=1);

namespace Hanwoolderink\Ollama\Laravel\Commands;

use Hanwoolderink\Ollama\Dtos\Message;
use Hanwoolderink\Ollama\Laravel\Facades\Ollama;
use Illuminate\Console\Command;

class OllamaAskCommand extends Command
{
    protected $signature = 'ollama:ask {model} {question}';

    protected $description = 'Ask a question to a model';

    public function handle(): void
    {
        /** @var string $model */
        $model = $this->argument('model');

        /** @var string $question */
        $question = $this->argument('question');

        $this->info("Model: $model");
        $this->info("Question: $question");

        Ollama::completion()->generate(
            model: $model,
            prompt: $question,
            stream: true,
            streamCallback: function (string $message) {
                $json = json_decode($message, true);

                if (is_array($json) && isset($json['response'])) {
                    echo $json['response'];
                }
            }
        );
    }
}
