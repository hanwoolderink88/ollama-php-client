<?php

declare(strict_types=1);

namespace Hanwoolderink\Ollama\Tests\Chat;

use Generator;
use Hanwoolderink\Ollama\Dtos\ChatResponse;
use Hanwoolderink\Ollama\Dtos\Message;
use Hanwoolderink\Ollama\Dtos\StreamResponse;
use Hanwoolderink\Ollama\Enums\Role;
use Hanwoolderink\Ollama\Tests\TestCase;

class ChatTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->ollama->model()->pull(self::$CompletionModel);
    }

    public function testChat(): void
    {
        $response = $this->ollama->chat()->create(
            model: self::$CompletionModel,
            messages: [
                new Message('Why is the sky blue?')
            ],
        );

        $this->assertInstanceOf(ChatResponse::class, $response);
    }

    public function testChatMessageAsStaticFactory(): void
    {
        $response = $this->ollama->chat()->create(
            model: self::$CompletionModel,
            messages: [
                Message::make('Why is the sky blue?'),
            ],
        );

        $this->assertInstanceOf(ChatResponse::class, $response);
    }

    public function testChatMessageAsArray(): void
    {
        $response = $this->ollama->chat()->create(
            model: self::$CompletionModel,
            messages: [
                ['role' => 'user', 'content' => 'Why is the sky blue?'],
            ],
        );

        $this->assertInstanceOf(ChatResponse::class, $response);
    }

    public function testChatWithHistory(): void
    {
        $response = $this->ollama->chat()->create(
            model: self::$CompletionModel,
            messages: [
                new Message('Why is the sky blue?'),
                new Message('Due to rayleigh scattering.', Role::ASSISTANT),
                new Message('Explain please?')
            ],
        );

        $this->assertInstanceOf(ChatResponse::class, $response);
    }

    public function testChatStream(): void
    {
        $response = $this->ollama->chat()->stream(
            model: self::$CompletionModel,
            messages: [
                new Message('Why does the sky appear more blue in the morning and more red in the evening?')
            ],
        );

        $this->assertInstanceOf(Generator::class, $response);

        foreach ($response as $streamResponse) {
            $this->assertInstanceOf(StreamResponse::class, $streamResponse);

            $array = $streamResponse->toArray();

            $this->assertArrayHasKey('model', $array);
            $this->assertArrayHasKey('created_at', $array);
            $this->assertArrayHasKey('message', $array);
            $this->assertArrayHasKey('done', $array);
        }
    }
}
