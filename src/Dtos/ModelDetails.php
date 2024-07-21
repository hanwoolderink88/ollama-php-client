<?php

declare(strict_types=1);

namespace Hanwoolderink\Ollama\Dtos;

/**
 * @property-read array<int, string> $families
 */
readonly class ModelDetails
{
    /**
     * @param  array<string,string>|null  $families
     */
    public function __construct(
        public string $parent_model,
        public string $format,
        public string $family,
        public array|null $families,
        public string $parameter_size,
        public string $quantization_level,
    ) {
    }

    /**
     * @param  array<string, mixed>  $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        /** @var array<string,string>|null $families */
        $families = $data['families'] ?? null;

        return new self(
            parent_model: $data['parent_model'],
            format: $data['format'],
            family: $data['family'],
            families: $families,
            parameter_size: $data['parameter_size'],
            quantization_level: $data['quantization_level'],
        );
    }
}
