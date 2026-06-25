<?php

declare(strict_types=1);

namespace RunApi\IdeogramV3\Resources;

use RunApi\Core\Http\HttpClient;
use RunApi\Core\Models\TaskCreateResponse;
use RunApi\Core\RequestOptions;
use RunApi\Core\Resources\TypedConfiguredResource;
use RunApi\IdeogramV3\Models\CompletedImageTaskResponse;
use RunApi\IdeogramV3\Models\ImageTaskResponse;
use RunApi\IdeogramV3\Types;

/**
 * Generates images from text prompts.
 */
readonly class TextToImage extends TypedConfiguredResource
{
    /**
     * Submits a text-to-image task and returns immediately with a task id.
     *
     * @param array{
     *   model: string,
     *   prompt: string,
     *   aspect_ratio?: string,
     *   callback_url?: string,
     *   output_count?: int,
     *   reference_image_urls?: list<string>,
     *   rendering_speed?: string,
     *   style?: string
     * } $params
     */
    public function create(array $params, ?RequestOptions $options = null): TaskCreateResponse
    {
        return parent::create($params, $options);
    }

    /**
     * Fetches the current status of a text-to-image task by id.
     */
    public function get(string $id, ?RequestOptions $options = null): ImageTaskResponse
    {
        $response = parent::get($id, $options);

        /** @var ImageTaskResponse $response */
        return $response;
    }

    /**
     * Submits a text-to-image task and polls until it completes.
     *
     * @param array{
     *   model: string,
     *   prompt: string,
     *   aspect_ratio?: string,
     *   callback_url?: string,
     *   output_count?: int,
     *   reference_image_urls?: list<string>,
     *   rendering_speed?: string,
     *   style?: string
     * } $params
     */
    public function run(array $params, ?RequestOptions $options = null): CompletedImageTaskResponse
    {
        $response = parent::run($params, $options);

        /** @var CompletedImageTaskResponse $response */
        return $response;
    }

    /**
     * Create the resource using the shared RunAPI HTTP transport.
     */
    public static function fromHttp(HttpClient $http): self
    {
        return new self(
            $http,
            '/api/v1/ideogram_v3/text_to_image',
            'ideogram-v3/text-to-image',
            ImageTaskResponse::class,
            CompletedImageTaskResponse::class,
            Types::TEXT_TO_IMAGE_MODELS,
            'text-to-image',
            ImageTaskResponse::class,
            CompletedImageTaskResponse::class,
        );
    }
}
