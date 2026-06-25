<?php

declare(strict_types=1);

namespace RunApi\IdeogramV3;

/**
 * Constants for model slugs supported by the Ideogram V3 PHP SDK.
 */
final class Types
{
    /** @var list<string> */
    public const TEXT_TO_IMAGE_MODELS = ['ideogram-v3-character', 'ideogram-v3-text-to-image'];

    /** @var list<string> */
    public const REMIX_IMAGE_MODELS = ['ideogram-v3-character-remix', 'ideogram-v3-remix'];

    /** @var list<string> */
    public const EDIT_IMAGE_MODELS = ['ideogram-v3-character-edit', 'ideogram-v3-edit'];

    /** @var list<string> */
    public const REFRAME_IMAGE_MODELS = ['ideogram-v3-reframe'];

    private function __construct()
    {
    }
}
