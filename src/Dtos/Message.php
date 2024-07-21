<?php

declare(strict_types=1);

namespace Hanwoolderink\Ollama\Dtos;

use Hanwoolderink\Ollama\Enums\Role;

class Message
{
    /**
     * @param  array<int, string>  $images  Base64 encoded images
     */
    public function __construct(
        public string $content,
        public Role $role = Role::USER,
        public ?array $images = null,
    ) {
    }

    /**
     * @param  array<string, mixed>  $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        /** @var string $roleStr */
        $roleStr = $data['role'];

        return new self(
            content: $data['content'],
            role: Role::from($roleStr),
            images: $data['images'] ?? null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'role' => $this->role->value,
            'content' => $this->content,
            'images' => $this->images,
        ]);
    }
}
