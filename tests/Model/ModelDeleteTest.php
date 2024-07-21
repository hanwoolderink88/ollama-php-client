<?php

declare(strict_types=1);

namespace Hanwoolderink\Ollama\Tests\Model;

use Hanwoolderink\Ollama\Exceptions\ModelNotFound;
use Hanwoolderink\Ollama\Ollama;
use Hanwoolderink\Ollama\Tests\TestCase;

class ModelDeleteTest extends TestCase
{
    public function testModelDelete(): void
    {
        $ollama = new Ollama();

        $ollama->model()->pull(self::$ModelName);

        $ollama->model()->delete(self::$ModelName);

        $this->expectException(ModelNotFound::class);
        $ollama->model()->show(self::$ModelName);
    }

    public function testUnknownModelDelete(): void
    {
        $ollama = new Ollama();

        $this->expectException(ModelNotFound::class);
        $ollama->model()->delete(self::$NonExistingModelName);
    }
}
