<?php

declare(strict_types=1);

namespace Hanwoolderink\Ollama\Http;

use Generator;
use GuzzleHttp\RequestOptions;
use Hanwoolderink\Ollama\Dtos\GenerationResponse;
use Hanwoolderink\Ollama\Dtos\StreamResponse;
use Hanwoolderink\Ollama\Exceptions\OllamaException;
use Hanwoolderink\Ollama\Http\Traits\HasJsonStreamResponse;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

// todo: format='json' seems to cut off the http response and return done=false with text not fully generated
class CompletionRequests extends AbstractRequest
{
    use HasJsonStreamResponse;

    /**
     * @param  array<int, string>|null  $images
     * @param  array<string, mixed>|null  $options
     * @param  array<int, int>|null  $context
     * @throws OllamaException
     */
    public function create(
        string $model,
        string $prompt,
        ?array $images = null,
        ?string $format = null,
        ?array $options = null,
        ?string $system = null,
        ?string $template = null,
        ?array $context = null,
        ?bool $raw = null,
    ): GenerationResponse {
        $response = $this->request('POST', '/api/generate', [
            RequestOptions::JSON => array_filter([
                'model' => $model,
                'prompt' => $prompt,
                'images' => $images,
                'format' => $format,
                'options' => $options,
                'system' => $system,
                'template' => $template,
                'context' => $context,
                'stream' => false,
                'raw' => $raw,
            ], fn ($value) => $value !== null),
            RequestOptions::STREAM => false,
        ]);

        $json = $this->json($response);

        return GenerationResponse::fromArray($json);
    }

    /**
     * @param  array<int, string>|null  $images
     * @param  array<string, mixed>|null  $options
     * @param  array<int, int>|null  $context
     * @throws OllamaException
     * @return Generator<StreamResponse>
     */
    public function stream(
        string $model,
        string $prompt,
        ?array $images = null,
        ?string $format = null,
        ?array $options = null,
        ?string $system = null,
        ?string $template = null,
        ?array $context = null,
        ?bool $raw = null,
    ): Generator {
        $response = $this->request('POST', '/api/generate', [
            RequestOptions::JSON => array_filter([
                'model' => $model,
                'prompt' => $prompt,
                'images' => $images,
                'format' => $format,
                'options' => $options,
                'system' => $system,
                'template' => $template,
                'context' => $context,
                'stream' => true,
                'raw' => $raw,
            ], fn ($value) => $value !== null),
            RequestOptions::STREAM => true,
        ]);

        return $this->streamResponse($response);
    }
}
