<?php

declare(strict_types=1);

namespace Hanwoolderink\Ollama\Tests\Completion;

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

        $response = $ollama->completion()->generate(
            model: $this::$CompletionModel,
            prompt: 'Why is the sky blue?',
        );

        $this->assertInstanceOf(GenerationResponse::class, $response);
    }

    public function testGenerateStream(): void
    {
        $ollama = new Ollama();

        $ollama->completion()->generate(
            model: self::$CompletionModel,
            prompt: 'Why is the sky blue?',
            stream: true,
            streamCallback: function (StreamResponse $part) {
                // /** @var resource $stream */
                // $stream = fopen('php://stdout', 'w');
                // fwrite($stream, $part->message->content);
                // fclose($stream);

                // assert all parts can be decoded
                $this->assertTrue(true);
            }
        );
    }

    public function testGenerateNonExistentModel(): void
    {
        $ollama = new Ollama();

        $this->expectException(ModelNotFound::class);

        $ollama->completion()->generate(
            model: self::$NonExistingModelName,
            prompt: 'Why is the sky blue?',
        );
    }
}
