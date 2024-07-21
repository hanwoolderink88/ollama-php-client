<?php

declare(strict_types=1);

namespace Hanwoolderink\Ollama\Enums;

enum Role: string
{
    case USER = 'user';
    case ASSISTANT = 'assistant';
    case SYSTEM = 'system';
}
