<?php

declare(strict_types=1);

namespace Hanwoolderink\Ollama\Http;

use GuzzleHttp\RequestOptions;
use Hanwoolderink\Ollama\Dtos\ModelList;
use Hanwoolderink\Ollama\Dtos\ModelShow;
use Hanwoolderink\Ollama\Exceptions\OllamaException;

class ModelRequests extends AbstactRequest
{
    /**
     * @return array<int, ModelList>
     * @throws OllamaException
     */
    public function list(): array
    {
        $response = $this->request('GET', '/api/tags');

        /** @var array<string,mixed> $models */
        $models = $this->json($response)['models'] ?? [];

        return array_values(array_map(function (array $data) {
            return ModelList::fromArray($data);
        }, $models));
    }

    /**
     * @throws OllamaException
     */
    public function show(string $name): ModelShow
    {
        $response = $this->request('POST', '/api/show', [
            RequestOptions::JSON => [
                'name' => $name,
            ]
        ]);

        /** @var array<string,mixed> $body */
        $body = $this->json($response);

        return ModelShow::fromArray($body);
    }

    /**
     * @return array<string, mixed>
     * @throws OllamaException
     */
    public function pull(string $name, bool $stream = false, bool $insecure = false): array
    {
        $response = $this->request('POST', '/api/pull', [
            RequestOptions::JSON => [
                'name' => $name,
                'stream' => $stream,
                'insecure' => $insecure,
            ]
        ]);

        return $this->json($response);
    }

    /**
     * @throws OllamaException
     */
    public function delete(string $name): void
    {
        $this->request('DELETE', '/api/delete', [
            RequestOptions::JSON => [
                'name' => $name,
            ]
        ]);
    }
}
