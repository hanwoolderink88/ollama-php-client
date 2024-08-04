<?php

declare(strict_types=1);

namespace Hanwoolderink\Ollama\Http\Traits;

use GuzzleHttp\Psr7\Stream;
use Hanwoolderink\Ollama\Dtos\StreamResponse;
use Psr\Http\Message\ResponseInterface;

trait HasJsonStreamResponse
{
    /**
     * @param  callable(StreamResponse $response):void  $callback
     */
    private function streamResponse(ResponseInterface $response, callable $callback): null
    {
        /** @var Stream $stream */
        $stream = $response->getBody();

        $previous = '';

        while (!$stream->eof()) {
            $content = $stream->read(128);

            $parts = explode("\n", $content);

            // fix first part with previous read
            $parts[0] = $previous.$parts[0];
            $previous = '';

            // store and remove last part
            $last = $parts[count($parts) - 1];
            if (strlen($last) > 0 && $last[-1] !== '}') {
                $previous = $last;
                unset($parts[count($parts) - 1]);
            }

            foreach ($parts as $part) {
                if (strlen($part) === 0) {
                    continue;
                }

                $json = json_decode($part, true, 512, JSON_THROW_ON_ERROR);

                if(!is_array($json)) {
                    dd($json);
                }

                $callback(StreamResponse::fromArray($json));
            }
        }

        return null;
    }
}
