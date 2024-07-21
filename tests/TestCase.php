<?php

declare(strict_types=1);

namespace Hanwoolderink\Ollama\Tests;

use Hanwoolderink\Ollama\Ollama;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    public static string $ModelName = 'hanwoolderink/test:latest';
    public static string $NonExistingModelName = 'non-existing/model:latest';
    public static string $CompletionModel = 'gemma2:latest';

    protected Ollama $ollama;

    public function setUp(): void
    {
        parent::setUp();

        $this->ollama = new Ollama();
    }
}
