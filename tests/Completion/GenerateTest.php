<?php

declare(strict_types=1);

namespace Hanwoolderink\Ollama\Tests\Completion;

use Generator;
use Hanwoolderink\Ollama\Dtos\GenerationResponse;
use Hanwoolderink\Ollama\Dtos\StreamResponse;
use Hanwoolderink\Ollama\Exceptions\ModelNotFound;
use Hanwoolderink\Ollama\Ollama;
use Hanwoolderink\Ollama\Tests\TestCase;

class GenerateTest extends TestCase
{
    public function testGenerate(): void
    {
        $ollama = new Ollama();

        $response = $ollama->completion()->create(
            model: $this::$CompletionModel,
            prompt: 'Why is the sky blue?',
        );

        $this->assertInstanceOf(GenerationResponse::class, $response);
    }

    public function testGenerateStream(): void
    {
        $ollama = new Ollama();

        $response = $ollama->completion()->stream(
            model: self::$CompletionModel,
            prompt: 'Why is the sky blue?',
        );

        $this->assertInstanceOf(Generator::class, $response);

        foreach ($response as $part) {
            $this->assertInstanceOf(StreamResponse::class, $part);
        }
    }

    public function testGenerateNonExistentModel(): void
    {
        $ollama = new Ollama();

        $this->expectException(ModelNotFound::class);

        $ollama->completion()->create(
            model: self::$NonExistingModelName,
            prompt: 'Why is the sky blue?',
        );
    }
}
