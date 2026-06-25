<?php

declare(strict_types=1);

namespace RunApi\IdeogramV3\Tests\Unit;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use RunApi\Core\ClientOptions;
use RunApi\Core\Errors\ValidationException;
use RunApi\Core\Tests\Fixtures\QueueHttpClient;
use RunApi\IdeogramV3\IdeogramV3Client;
use RunApi\IdeogramV3\Models\CompletedImageTaskResponse;
use RunApi\IdeogramV3\Resources\EditImage;
use RunApi\IdeogramV3\Resources\ReframeImage;
use RunApi\IdeogramV3\Resources\RemixImage;
use RunApi\IdeogramV3\Resources\TextToImage;

final class IdeogramV3ClientTest extends TestCase
{
    public function testExposesTypedResources(): void
    {
        $client = new IdeogramV3Client(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        self::assertInstanceOf(TextToImage::class, $client->textToImage);
        self::assertInstanceOf(RemixImage::class, $client->remixImage);
        self::assertInstanceOf(EditImage::class, $client->editImage);
        self::assertInstanceOf(ReframeImage::class, $client->reframeImage);
    }

    public function testCreatePostsCompactedBodyToCorrectPath(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
        ]);
        $client = new IdeogramV3Client(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $task = $client->textToImage->create([
            'model' => 'ideogram-v3-text-to-image',
            'prompt' => 'A product render',
            'callback_url' => '',
            'seed' => null,
        ]);

        $body = json_decode((string) $transport->requests[0]->getBody(), true, flags: JSON_THROW_ON_ERROR);

        self::assertSame('task_1', $task->id);
        self::assertSame('/api/v1/ideogram_v3/text_to_image', $transport->requests[0]->getUri()->getPath());
        self::assertSame('ideogram-v3-text-to-image', $body['model']);
        self::assertArrayNotHasKey('callback_url', $body);
        self::assertArrayNotHasKey('seed', $body);
    }

    public function testRunReturnsTypedCompletedResponseAndPreservesUnknownFields(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
            new Response(200, [], '{"id":"task_1","status":"completed","images":[{"url":"https://file.runapi.ai/result"}],"extra_field":"kept"}'),
        ]);
        $client = new IdeogramV3Client(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $result = $client->textToImage->run([
            'model' => 'ideogram-v3-text-to-image',
            'prompt' => 'A product render',
        ]);

        self::assertInstanceOf(CompletedImageTaskResponse::class, $result);
        self::assertSame('https://file.runapi.ai/result', $result->images[0]->url);
        self::assertSame('kept', $result->toArray()['extra_field']);
        self::assertSame('/api/v1/ideogram_v3/text_to_image/task_1', $transport->requests[1]->getUri()->getPath());
    }

    public function testCompletedResponseRequiresResultFiles(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
            new Response(200, [], '{"id":"task_1","status":"completed"}'),
        ]);
        $client = new IdeogramV3Client(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('images is required');

        $client->textToImage->run([
            'model' => 'ideogram-v3-text-to-image',
            'prompt' => 'A product render',
        ]);
    }

    public function testRejectsInvalidContractEnum(): void
    {
        $client = new IdeogramV3Client(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('aspect_ratio must be one of the allowed values');

        $client->textToImage->create([
        'model' => 'ideogram-v3-text-to-image',
        'prompt' => 'A product render',
        'aspect_ratio' => 'not-valid',
        ]);
    }

    public function testSecondaryResourceUsesItsOwnPath(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_2"}'),
        ]);
        $client = new IdeogramV3Client(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $client->remixImage->create([
            'model' => 'ideogram-v3-character-remix',
            'prompt' => 'A product render',
            'reference_image_urls' => ['https://cdn.runapi.ai/public/samples/portrait.jpg'],
            'source_image_url' => 'https://cdn.runapi.ai/public/samples/image.jpg',
            'style_reference_image_urls' => ['https://cdn.runapi.ai/public/samples/image.jpg'],
        ]);

        self::assertSame('/api/v1/ideogram_v3/remix_image', $transport->requests[0]->getUri()->getPath());
    }
}
