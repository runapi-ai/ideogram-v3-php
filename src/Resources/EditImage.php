<?php

declare(strict_types=1);

namespace RunApi\IdeogramV3\Resources;

use RunApi\Core\Http\HttpClient;
use RunApi\Core\Models\TaskCreateResponse;
use RunApi\Core\RequestOptions;
use RunApi\Core\Resources\TypedConfiguredResource;
use RunApi\IdeogramV3\Models\CompletedImageTaskResponse;
use RunApi\IdeogramV3\Models\ImageTaskResponse;

/**
 * Inpaints a source image using a mask.
 */
readonly class EditImage extends TypedConfiguredResource
{
    /**
     * Submits an edit-image task and returns immediately with a task id.
     *
     * @param array{
     *   model: string,
     *   prompt: string,
     *   mask_url: string,
     *   source_image_url: string,
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
     * Fetches the current status of an edit-image task by id.
     */
    public function get(string $id, ?RequestOptions $options = null): ImageTaskResponse
    {
        $response = parent::get($id, $options);

        /** @var ImageTaskResponse $response */
        return $response;
    }

    /**
     * Submits an edit-image task and polls until it completes.
     *
     * @param array{
     *   model: string,
     *   prompt: string,
     *   mask_url: string,
     *   source_image_url: string,
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
            '/api/v1/ideogram_v3/edit_image',
            ImageTaskResponse::class,
            CompletedImageTaskResponse::class,
            'edit-image',
            ImageTaskResponse::class,
            CompletedImageTaskResponse::class,
        );
    }
}
