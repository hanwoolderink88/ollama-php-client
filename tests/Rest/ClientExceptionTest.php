<?php

declare(strict_types=1);

namespace Hanwoolderink\Ollama\Tests\Rest;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Hanwoolderink\Ollama\Exceptions\OllamaException;
use Hanwoolderink\Ollama\Ollama;
use Hanwoolderink\Ollama\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Throwable;

class ClientExceptionTest extends TestCase
{
    /**
     * @return array<int, mixed>
     */
    public static function errorProvider(): array
    {
        $request = new Request('GET', 'test');

        return [
            [
                // unknown error status code
                new ClientException('', $request, new Response(500, [], 'Not Found')),
                OllamaException::class,
            ],
            [
                // unknown error status code
                new ServerException('', $request, new Response(404, [], 'Not Found')),
                OllamaException::class,
            ],
            [
                // unknown error body
                new ServerException('unknown body', $request, new Response(500, [], 'Not Found')),
                OllamaException::class,
            ],
            [
                // unknown error type
                new Exception(),
                OllamaException::class,
            ],
        ];
    }

    /**
     * @param  class-string<Throwable>  $expectedE
     */
    #[DataProvider('errorProvider')]
    public function testGenericClientException(Exception $e, string $expectedE): void
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler([$e]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $ollama = new Ollama(client: $client);
        $this->expectException($expectedE);

        $ollama->model()->list();
    }
}
