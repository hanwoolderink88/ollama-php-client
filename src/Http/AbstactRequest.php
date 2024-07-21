<?php

declare(strict_types=1);

namespace Hanwoolderink\Ollama\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use Hanwoolderink\Ollama\Exceptions\ConnectionException;
use Hanwoolderink\Ollama\Exceptions\ModelNotFound;
use Hanwoolderink\Ollama\Exceptions\OllamaException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class AbstactRequest
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->setClient($client);
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    protected function setClient(Client $client): void
    {
        $this->client = $client;
    }

    /**
     * @param  array<string,mixed>  $options
     * @throws OllamaException
     */
    protected function request(string $method, string $uri, array $options = []): ResponseInterface
    {
        try {
            return $this->getClient()->request($method, $uri, $options);
        } catch (ConnectException $e) {
            throw new ConnectionException(previous: $e);
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() === 404) {
                throw new ModelNotFound(previous: $e);
            }

            throw new OllamaException(previous: $e);
        } catch (ServerException $e) {
            $code = $e->getResponse()->getStatusCode();
            $body = $e->getResponse()->getBody()->getContents();

            if ($code === 500 && $body === '{"error":"pull model manifest: file does not exist"}') {
                throw new ModelNotFound(previous: $e);
            }

            if ($code === 500 && str_contains($body, 'no such file or directory')) {
                throw new ModelNotFound();
            }

            throw new OllamaException(previous: $e);
        } catch (Throwable $e) {
            throw new OllamaException(previous: $e);
        }
    }

    /**
     * @return array<string,mixed>
     */
    protected function json(ResponseInterface $response): array
    {
        /** @var array<string,mixed> $return */
        $return = json_decode($response->getBody()->getContents(), true);

        return $return;
    }
}
