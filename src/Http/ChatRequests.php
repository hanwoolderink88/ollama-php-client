<?php

declare(strict_types=1);

namespace Hanwoolderink\Ollama\Http;

use GuzzleHttp\RequestOptions;
use Hanwoolderink\Ollama\Dtos\ChatResponse;
use Hanwoolderink\Ollama\Dtos\Message;
use Hanwoolderink\Ollama\Http\Traits\HasJsonStreamResponse;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

class ChatRequests extends AbstactRequest
{
    use HasJsonStreamResponse;

    /**
     * @param  array<int, Message>  $messageHistory
     * @param  array<string, mixed>|null  $options  https://github.com/ollama/ollama/blob/main/docs/modelfile.md#valid-parameters-and-values
     * @param  callable(string $response): void|null  $streamCallback
     */
    public function message(
        string $model,
        Message $message,
        array $messageHistory = [], // From oldest to newest
        ?string $format = null,
        ?array $options = null,
        ?string $keepAlive = null,
        bool $stream = false,
        ?callable $streamCallback = null,
    ): ?ChatResponse {
        if($stream && $streamCallback === null) {
            throw new InvalidArgumentException('streamCallback must be provided when stream is true');
        }

        $messageHistory[] = $message;

        $body = array_filter([
            'model' => $model,
            'messages' => array_map(fn (Message $message) => $message->toArray(), $messageHistory),
            'format' => $format,
            'options' => $options,
            'keepAlive' => $keepAlive,
            'stream' => $stream,
        ], fn ($value) => $value !== null);

        $response = $this->request('POST', 'api/chat', [
            RequestOptions::JSON => $body,
            RequestOptions::STREAM => $stream,
        ]);

        return $stream ? $this->streamResponse($response, $streamCallback) : $this->response($response);
    }

    private function response(ResponseInterface $response): ChatResponse
    {
        $json = $this->json($response);

        return ChatResponse::fromArray($json);
    }
}
