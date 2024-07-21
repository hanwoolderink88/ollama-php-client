<?php

declare(strict_types=1);

namespace Hanwoolderink\Ollama\Dtos;

readonly class ModelShow
{
    public ModelDetails $details;

    /**
     * @param  array<string,mixed>|ModelDetails  $details
     * @param  array<string,mixed>  $model_info
     */
    public function __construct(
        public string $modelfile,
        public string $parameters,
        public string $template,
        array|ModelDetails $details,
        public array $model_info,
        public string $modified_at,
        public ?string $license = null,
    ) {
        $this->details = $details instanceof ModelDetails ? $details : ModelDetails::fromArray($details);
    }

    /**
     * @param  array<string,mixed>  $data
     * @return ModelShow
     */
    public static function fromArray(array $data): ModelShow
    {
        /** @var ModelDetails $details */
        $details = is_array($data['details']) ? ModelDetails::fromArray($data['details']) : $data['details'];

        return new ModelShow(
            modelfile: $data['modelfile'],
            parameters: $data['parameters'],
            template: $data['template'],
            details: $details,
            model_info: $data['model_info'],
            modified_at: $data['modified_at'],
            license: $data['license'] ?? null,
        );
    }
}
