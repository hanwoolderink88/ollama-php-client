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

$response = $ollama->chat()->message(
  model: 'llama3', 
  message: new Message('Why is the sky blue?')
);

echo $response->message->content;
```

Stream example:
```php
use Hanwoolderink\Ollama\Ollama;
use Hanwoolderink\Ollama\Dtos\Message;

$ollama = new Ollama();

$response = $ollama->chat()->message(
  model: 'llama3', 
  message: new Message('Why is the sky blue?'),
  stream: true, 
  streamCallback: function(string $response) {
      $json = json_decode($response, true);

      // see it streaming (e.g. in a console)
      $stream = fopen('php://stdout', 'w');
      fwrite($stream, $json['message']['content']);
      fclose($stream);
  }
);
```