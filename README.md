# Ideogram V3 PHP SDK for RunAPI

[![Packagist](https://img.shields.io/packagist/v/runapi-ai/ideogram-v3)](https://packagist.org/packages/runapi-ai/ideogram-v3)
[![License](https://img.shields.io/github/license/runapi-ai/ideogram-v3-php)](https://github.com/runapi-ai/ideogram-v3-php/blob/main/LICENSE)

The Ideogram V3 PHP SDK is the language-specific package for Ideogram V3
on RunAPI. Use this package when your application needs Composer installs,
associative-array request bodies, task status lookup, and consistent RunAPI
errors in PHP.

This README is the PHP package guide for the public `ideogram-v3-php` split
repository. For model details, use https://runapi.ai/models/ideogram-v3; for API
reference, use https://runapi.ai/docs/api/ideogram-v3/text-to-image; for SDK docs, use
https://runapi.ai/docs/resources/sdks.

## Install

```bash
composer require runapi-ai/ideogram-v3
```

## Quick start

```php
<?php

require __DIR__ . "/vendor/autoload.php";

use RunApi\IdeogramV3\IdeogramV3Client;

$client = new IdeogramV3Client(); // reads RUNAPI_API_KEY

$remixImageTask = $client->remixImage->create([
    'model' => 'ideogram-v3-character-remix',
    'aspect_ratio' => '1:1',
    'enable_prompt_expansion' => true,
    'negative_prompt' => 'sample',
    'output_count' => 1,
    'prompt' => 'Make it golden hour',
    'reference_image_urls' => ['https://cdn.runapi.ai/public/samples/image.jpg'],
    'reference_mask_urls' => ['sample'],
    'rendering_speed' => 'turbo',
    'seed' => 1,
    'source_image_url' => 'https://cdn.runapi.ai/public/samples/image.jpg',
    'strength' => 0.5,
    'style' => 'auto',
    'style_reference_image_urls' => ['https://cdn.runapi.ai/public/samples/image.jpg'],
]);

$task = $client->textToImage->create([
    'model' => 'ideogram-v3-character',
    'aspect_ratio' => '1:1',
    'enable_prompt_expansion' => true,
    'negative_prompt' => 'sample',
    'output_count' => 1,
    'prompt' => 'A precise product render on white marble',
    'reference_image_urls' => ['https://cdn.runapi.ai/public/samples/image.jpg'],
    'rendering_speed' => 'turbo',
    'seed' => 1,
    'style' => 'auto',
]);

$status = $client->textToImage->get($task->id);

$result = $client->textToImage->run([
    'model' => 'ideogram-v3-character',
    'aspect_ratio' => '1:1',
    'enable_prompt_expansion' => true,
    'negative_prompt' => 'sample',
    'output_count' => 1,
    'prompt' => 'A serene mountain lake at dawn',
    'reference_image_urls' => ['https://cdn.runapi.ai/public/samples/image.jpg'],
    'rendering_speed' => 'turbo',
    'seed' => 1,
    'style' => 'auto',
]);

echo $result->images[0]->url . PHP_EOL;
```

Use `create()` to submit a task and return quickly, `get()` to fetch the latest
task state, and `run()` when a script should create and poll until completion.
In web request handlers, prefer `create()` plus webhook or later `get()`
polling so a worker is not held open.


RunAPI-generated file URLs are temporary. Download and store generated files
in your own durable storage within the retention window; do not treat returned
URLs as long-term assets.

## Language notes

Pass request parameters as associative arrays with snake_case keys. The
available resources are `textToImage`, `remixImage`, `editImage`, `reframeImage`. Keep `RUNAPI_API_KEY` in the environment
or your secret manager; never commit API keys or callback secrets.

## Links

- Model page: https://runapi.ai/models/ideogram-v3
- SDK docs: https://runapi.ai/docs/resources/sdks
- Product docs: https://runapi.ai/docs/api/ideogram-v3/text-to-image
- Pricing and rate limits: https://runapi.ai/models/ideogram-v3/text-to-image
- Full catalog: https://runapi.ai/models
- GitHub repository: https://github.com/runapi-ai/ideogram-v3-php
- Multi-language SDK repository: https://github.com/runapi-ai/ideogram-v3-sdk

## License

Licensed under the Apache License, Version 2.0.
