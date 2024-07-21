<?php

declare(strict_types=1);

namespace Hanwoolderink\Ollama\Tests\Model;

use GuzzleHttp\Client;
use Hanwoolderink\Ollama\Exceptions\ConnectionException;
use Hanwoolderink\Ollama\Exceptions\ModelNotFound;
use Hanwoolderink\Ollama\Ollama;
use Hanwoolderink\Ollama\Tests\TestCase;

class ModelPullTest extends TestCase
{
    public function testModelCanBePulled(): void
    {
        $ollama = new Ollama();

        $result = $ollama->model()->pull(self::$ModelName);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('status', $result);
        $this->assertEquals('success', $result['status']);
    }

    public function testUnknownModelCannotBePulled(): void
    {
        $ollama = new Ollama();

        $this->expectException(ModelNotFound::class);

        $ollama->model()->pull(self::$NonExistingModelName);
    }

    public function testNotConnected(): void
    {
        $this->expectException(ConnectionException::class);

        $client = new Client([
            'base_uri' => 'http://no-route:11434',
        ]);

        $ollama = new Ollama($client);

        $ollama->model()->pull(self::$ModelName);
    }
}
