<?php

declare(strict_types=1);

namespace Hanwoolderink\Ollama;

use GuzzleHttp\Client;
use Hanwoolderink\Ollama\Http\ChatRequests;
use Hanwoolderink\Ollama\Http\CompletionRequests;
use Hanwoolderink\Ollama\Http\ModelRequests;

class Ollama
{
    private Client $client;

    public function __construct(Client $client = null)
    {
        $this->client = $client ?? new Client([
            'base_uri' => 'http://127.0.0.1:11434',
        ]);
    }

    public function model(): ModelRequests
    {
        return new ModelRequests($this->client);
    }

    public function completion(): CompletionRequests
    {
        return new CompletionRequests($this->client);
    }

    public function chat(): ChatRequests
    {
        return new ChatRequests($this->client);
    }
}
