<?php

declare(strict_types=1);

namespace Hanwoolderink\Ollama\Dtos;

use DateTime;
use Hanwoolderink\Ollama\Enums\Role;

class StreamResponse
{
    public function __construct(
        public string $model,
        public DateTime $createdAt,
        public Message $message,
        public bool $done,
    ) {
    }

    /**
     * @param array<string, mixed> $array
     */
    public static function fromArray(array $array): self
    {
        /** @var string $dateAtString */
        $dateAtString = $array['created_at'];

        /** @var array<string, mixed> $message */
        $message = $array['message'] ?? [];

        return new self(
            model: $array['model'],
            createdAt: new DateTime($dateAtString),
            message: new Message(
                content: $message['content'] ?? '',
                role: Role::ASSISTANT,
                images: $message['images'] ?? null,
            ),
            done: $array['done'],
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'model' => $this->model,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'message' => $this->message->toArray(),
            'done' => $this->done,
        ];
    }
}
