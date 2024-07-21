<?php

declare(strict_types=1);

namespace Hanwoolderink\Ollama\Dtos;

readonly class ModelList
{
    public ModelDetails $details;

    /**
     * @param  array<string, mixed>|ModelDetails  $details
     */
    public function __construct(
        public string $name,
        public string $model,
        public string $modified_at,
        public int $size,
        public string $digest,
        array|ModelDetails $details,
        public ?string $license = null,
    ) {
        $this->details = $details instanceof ModelDetails ? $details : ModelDetails::fromArray($details);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        /** @var ModelDetails $details */
        $details = is_array($data['details']) ? ModelDetails::fromArray($data['details']) : $data['details'];

        return new self(
            name: $data['name'],
            model: $data['model'],
            modified_at: $data['modified_at'],
            size: $data['size'],
            digest: $data['digest'],
            details: $details,
            license: $data['license'] ?? null,
        );
    }
}
