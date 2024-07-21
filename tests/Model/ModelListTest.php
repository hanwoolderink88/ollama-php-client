<?php

declare(strict_types=1);

namespace Hanwoolderink\Ollama\Tests\Model;

use Hanwoolderink\Ollama\Dtos\ModelList;
use Hanwoolderink\Ollama\Dtos\ModelDetails;
use Hanwoolderink\Ollama\Ollama;
use Hanwoolderink\Ollama\Tests\TestCase;

final class ModelListTest extends TestCase
{
    public function testModelListCanBeRetrieved(): void
    {
        $ollama = new Ollama();

        $result = $ollama->model()->list();

        $this->assertIsArray($result);

        foreach ($result as $model) {
            $this->assertInstanceOf(ModelList::class, $model);
            $this->assertInstanceOf(ModelDetails::class, $model->details);
        }
    }
}
