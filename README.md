# Ollama API PHP client

PHP client for the Ollama API. It provides a simple way to interact with the API.

See the [Ollama API documentation](https://github.com/ollama/ollama/blob/main/docs/api.md) for more information.

## Installation

You can install the package via composer:

```bash
composer require hanwoolderink/ollama-php-client
```

## Usage

Basic usage example:

```php
use Hanwoolderink\Ollama\Ollama;
use Hanwoolderink\Ollama\Dtos\Message;

$ollama = new Ollama();

$response = $ollama->chat()->create(
  model: 'llama3.1:latest', 
  message: new Message('Why is the sky blue?')
);

echo $response->message->content;
```

Stream example:

```php
use Hanwoolderink\Ollama\Ollama;
use Hanwoolderink\Ollama\Dtos\Message;

$ollama = new Ollama();

$response = $ollama->chat()->stream(
    model: 'llama3.1:latest',
    messages: [
        new Message('Why does the sky appear more blue in the morning and more red in the evening?')
    ],
);

foreach ($response as $streamResponse) {
    // update storage, socket, etc.. This prints to cli
    $stream = fopen('php://stdout', 'w');
    fwrite($stream, $streamResponse->message->content);
    fclose($stream);
}
```