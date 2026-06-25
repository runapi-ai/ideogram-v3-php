<?php

declare(strict_types=1);

namespace RunApi\IdeogramV3;

use RunApi\Core\BaseClient;
use RunApi\Core\ClientOptions;
use RunApi\IdeogramV3\Resources\EditImage;
use RunApi\IdeogramV3\Resources\ReframeImage;
use RunApi\IdeogramV3\Resources\RemixImage;
use RunApi\IdeogramV3\Resources\TextToImage;

/**
 * The Ideogram V3 image generation API client.
 *
 * Exposes typed model resources plus the universal files and account resources.
 */
final class IdeogramV3Client extends BaseClient
{
    /**
     * Provides text-to-image operations.
     */
    public readonly TextToImage $textToImage;
    /**
     * Provides image remix operations.
     */
    public readonly RemixImage $remixImage;
    /**
     * Provides inpaint-with-mask operations.
     */
    public readonly EditImage $editImage;
    /**
     * Provides image reframe operations.
     */
    public readonly ReframeImage $reframeImage;

    /**
     * Create an Ideogram V3 client with optional API key, base URL, and transport overrides.
     */
    public function __construct(ClientOptions $options = new ClientOptions())
    {
        parent::__construct($options);
        $this->textToImage = TextToImage::fromHttp($this->http);
        $this->remixImage = RemixImage::fromHttp($this->http);
        $this->editImage = EditImage::fromHttp($this->http);
        $this->reframeImage = ReframeImage::fromHttp($this->http);
    }
}
