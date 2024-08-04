<?php

declare(strict_types=1);

namespace Hanwoolderink\Ollama\Http;

use GuzzleHttp\RequestOptions;
use Hanwoolderink\Ollama\Dtos\GenerationResponse;
use Hanwoolderink\Ollama\Dtos\StreamResponse;
use Hanwoolderink\Ollama\Exceptions\OllamaException;
use Hanwoolderink\Ollama\Http\Traits\HasJsonStreamResponse;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

// todo: format='json' seems to cut off the http response and return done=false with text not fully generated
class CompletionRequests extends AbstactRequest
{
    use HasJsonStreamResponse;

    /**
     * @param  array<int, string>|null  $images
     * @param  array<string, mixed>|null  $options
     * @param  array<int, int>|null  $context
     * @param  callable(StreamResponse $response): void  $streamCallback
     * @throws OllamaException
     */
    public function generate(
        string $model,
        string $prompt,
        ?array $images = null,
        ?string $format = null,
        ?array $options = null,
        ?string $system = null,
        ?string $template = null,
        ?array $context = null,
        ?bool $raw = null,
        bool $stream = false,
        ?callable $streamCallback = null,
    ): ?GenerationResponse {
        if($stream && $streamCallback === null) {
            throw new InvalidArgumentException('streamCallback must be provided when stream is true');
        }

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
                'stream' => $stream,
                'raw' => $raw,
            ], fn ($value) => $value !== null),
            RequestOptions::STREAM => $stream,
        ]);

        return $stream ? $this->streamResponse($response, $streamCallback) : $this->response($response);
    }

    private function response(ResponseInterface $response): GenerationResponse
    {
        $json = $this->json($response);

        return GenerationResponse::fromArray($json);
    }
}
