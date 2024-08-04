<?php

declare(strict_types=1);

namespace Hanwoolderink\Ollama\Tests\Chat;

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
        $response = $this->ollama->chat()->message(
            model: self::$CompletionModel,
            messages: [new Message('Why is the sky blue?')],
        );

        $this->assertInstanceOf(ChatResponse::class, $response);
    }

    public function testChatWithHistory(): void
    {
        $response = $this->ollama->chat()->message(
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
        $this->ollama->chat()->message(
            model: self::$CompletionModel,
            messages: [new Message('Why does the sky appear more blue in the morning and more red in the evening?')],
            stream: true,
            streamCallback: function (StreamResponse $response) {
                // /** @var resource $stream */
                // $stream = fopen('php://stdout', 'w');
                // fwrite($stream, $response->message->content);
                // fclose($stream);

                $this->assertTrue(true);
            },
        );
    }
}
