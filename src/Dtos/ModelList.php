<?php

declare(strict_types=1);

namespace Hanwoolderink\Ollama\Dtos;

use DateTime;

readonly class ModelList
{
    public ModelDetails $details;

    /**
     * @param  array<string, mixed>|ModelDetails  $details
     */
    public function __construct(
        public string $name,
        public string $model,
        public DateTime $modified_at,
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

        /** @var string $modifiedAtStr */
        $modifiedAtStr = $data['modified_at'];

        return new self(
            name: $data['name'],
            model: $data['model'],
            modified_at: new DateTime($modifiedAtStr),
            size: $data['size'],
            digest: $data['digest'],
            details: $details,
            license: $data['license'] ?? null,
        );
    }

    public function sizeReadable(): string
    {
        return round($this->size / 1000000, 1).'GB';
    }
}
