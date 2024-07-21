<?php

declare(strict_types=1);

namespace Hanwoolderink\Ollama\Laravel;

use Hanwoolderink\Ollama\Ollama as OllamaConcrete;
use Illuminate\Support\Facades\Facade;

class Ollama extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return OllamaConcrete::class;
    }
}
