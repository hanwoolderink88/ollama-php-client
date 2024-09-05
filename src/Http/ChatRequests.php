<?php

declare(strict_types=1);

namespace Hanwoolderink\Ollama\Http;

use Generator;
use GuzzleHttp\RequestOptions;
use Hanwoolderink\Ollama\Dtos\ChatResponse;
use Hanwoolderink\Ollama\Dtos\Message;
use Hanwoolderink\Ollama\Dtos\StreamResponse;
use Hanwoolderink\Ollama\Http\Traits\HasJsonStreamResponse;

class ChatRequests extends AbstractRequest
{
    use HasJsonStreamResponse;

    /**
     * @param array<int, Message|array<string, string>> $messages
     * @param array<string, mixed>|null $options https://github.com/ollama/ollama/blob/main/docs/modelfile.md#valid-parameters-and-values
     */
    public function create(
        string $model,
        array $messages,
        ?string $format = null,
        ?array $options = null,
        ?string $keepAlive = null,
    ): ?ChatResponse {
        $response = $this->request('POST', 'api/chat', [
            RequestOptions::JSON => $this->requestBody($model, $messages, $format, $options, $keepAlive, false),
            RequestOptions::STREAM => false,
        ]);

        $json = $this->json($response);

        return ChatResponse::fromArray($json);
    }

    /**
     * @param array<int, Message|array<string, mixed>> $messages
     * @param array<string, mixed>|null $options
     *
     * @return Generator<StreamResponse>
     */
    public function stream(
        string $model,
        array $messages,
        ?string $format = null,
        ?array $options = null,
        ?string $keepAlive = null,
    ): Generator {
        $response = $this->request('POST', 'api/chat', [
            RequestOptions::JSON => $this->requestBody($model, $messages, $format, $options, $keepAlive, true),
            RequestOptions::STREAM => true,
        ]);

        return $this->streamResponse($response);
    }

    /**
     * @param array<int, Message|array<string, mixed>> $messages
     * @param array<string, mixed>|null $options
     * @return array<string, mixed>
     */
    private function requestBody(
        string $model,
        array $messages,
        ?string $format,
        ?array $options,
        ?string $keepAlive,
        bool $stream
    ): array {
        return array_filter([
            'model' => $model,
            'messages' => array_map(function (Message|array $message) {
                return $message instanceof Message ? $message->toArray() : $message;
            }, $messages),
            'format' => $format,
            'options' => $options,
            'keepAlive' => $keepAlive,
            'stream' => $stream,
        ], fn ($value) => $value !== null);
    }
}
