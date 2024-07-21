<?php

declare(strict_types=1);

namespace Hanwoolderink\Ollama\Dtos;

use DateTime;
use Exception;

class GenerationResponse
{
    /**
     * @param  array<int, int>  $context
     */
    public function __construct(
        public string $model,
        public DateTime $created_at,
        public string $response,
        public bool $done,
        public string $done_reason,
        public array $context,
        public int $total_duration,
        public int $prompt_eval_count,
        public int $prompt_eval_duration,
        public int $eval_count,
        public int $eval_duration,
    ) {
    }

    /**
     * @param  array<string, mixed>  $data
     * @return self
     * @throws Exception
     */
    public static function fromArray(array $data): self
    {
        /** @var string $createdAtString */
        $createdAtString = $data['created_at'];

        return new self(
            model: $data['model'],
            created_at: new DateTime($createdAtString),
            response: $data['response'],
            done: $data['done'],
            done_reason: $data['done_reason'],
            context: $data['context'],
            total_duration: $data['total_duration'],
            prompt_eval_count: $data['prompt_eval_count'],
            prompt_eval_duration: $data['prompt_eval_duration'],
            eval_count: $data['eval_count'],
            eval_duration: $data['eval_duration'],
        );
    }
}
