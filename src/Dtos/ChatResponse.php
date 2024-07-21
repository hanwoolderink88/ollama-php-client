<?php

declare(strict_types=1);

namespace Hanwoolderink\Ollama\Dtos;

use DateTime;

class ChatResponse
{
    public function __construct(
        public string $model,
        public DateTime $created_at,
        public Message $message,
        public string $done_reason,
        public bool $done,
        public int $total_duration,
        public int $load_duration,
        public int $prompt_eval_count,
        public int $prompt_eval_duration,
        public int $eval_count,
        public int $eval_duration,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        /** @var string $createdAtString */
        $createdAtString = $data['created_at'];

        /** @var array<string, mixed> $messageData */
        $messageData = $data['message'];

        return new self(
            model: $data['model'],
            created_at: new DateTime($createdAtString),
            message: Message::fromArray($messageData),
            done_reason: $data['done_reason'],
            done: $data['done'],
            total_duration: $data['total_duration'],
            load_duration: $data['load_duration'],
            prompt_eval_count: $data['prompt_eval_count'],
            prompt_eval_duration: $data['prompt_eval_duration'],
            eval_count: $data['eval_count'],
            eval_duration: $data['eval_duration'],
        );
    }
}
