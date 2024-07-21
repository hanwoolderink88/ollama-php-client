<?php

declare(strict_types=1);

namespace Hanwoolderink\Ollama\Tests\Model;

use Hanwoolderink\Ollama\Dtos\ModelShow;
use Hanwoolderink\Ollama\Exceptions\ModelNotFound;
use Hanwoolderink\Ollama\Ollama;
use Hanwoolderink\Ollama\Tests\TestCase;

class ModelShowTest extends TestCase
{
    public function testModelCanBeRetrieved(): void
    {
        $ollama = new Ollama();

        $ollama->model()->pull(self::$ModelName);

        $result = $ollama->model()->show(self::$ModelName);

        $this->assertInstanceOf(ModelShow::class, $result);
    }

    public function testModelNotFound(): void
    {
        $this->expectException(ModelNotFound::class);

        $ollama = new Ollama();

        $ollama->model()->show(self::$NonExistingModelName);
    }
}
