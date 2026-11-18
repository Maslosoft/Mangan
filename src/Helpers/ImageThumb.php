<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link https://maslosoft.com/mangan/
 */

declare(strict_types=1);

namespace Maslosoft\Mangan\Helpers;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use InvalidArgumentException;

/** Image operations used by Mangan's GridFS image model. */
class ImageThumb
{
    private ImageInterface $image;
    private string $format;

    public function __construct(string $fileName, private array $options = [])
    {
        $info = getimagesize($fileName);
        $this->format = match ($info[2] ?? null) {
            IMAGETYPE_JPEG => 'jpeg',
            IMAGETYPE_PNG => 'png',
            IMAGETYPE_GIF => 'gif',
            default => throw new InvalidArgumentException('Unsupported image format.'),
        };
        $this->image = (new Imagine())->open($fileName);
    }

    public function resize(int $maxWidth = 0, int $maxHeight = 0): static
    {
        if ($maxWidth < 0 || $maxHeight < 0) {
            throw new InvalidArgumentException('Image dimensions cannot be negative.');
        }
        if ($maxWidth === 0 && $maxHeight === 0) {
            return $this;
        }
        $size = $this->image->getSize();
        // Zero means unconstrained, including when upscaling is enabled.
        if ($maxWidth === 0) {
            $maxWidth = max(1, (int) round($size->getWidth() * $maxHeight / $size->getHeight()));
        }
        if ($maxHeight === 0) {
            $maxHeight = max(1, (int) round($size->getHeight() * $maxWidth / $size->getWidth()));
        }
        $this->image = $this->image->thumbnail(new Box($maxWidth, $maxHeight), $this->flags(ImageInterface::THUMBNAIL_INSET));
        return $this;
    }

    public function adaptiveResize(int $width, int $height): static
    {
        if ($width < 0 || $height < 0 || ($width === 0 && $height === 0)) {
            throw new InvalidArgumentException('Adaptive resize requires at least one positive dimension and no negative dimensions.');
        }
        if ($width === 0 || $height === 0) {
            return $this->resize($width, $height);
        }
        $this->image = $this->image->thumbnail(new Box($width, $height), $this->flags(ImageInterface::THUMBNAIL_OUTBOUND));
        return $this;
    }

    public function getCurrentDimensions(): array
    {
        $size = $this->image->getSize();
        return ['width' => $size->getWidth(), 'height' => $size->getHeight()];
    }

    public function getImageAsString(): string
    {
        return $this->image->get($this->format, $this->encodingOptions());
    }

    public function save(string $fileName, ?string $format = null): static
    {
        $format = strtolower($format ?? $this->format);
        $this->image->save($fileName, ['format' => $format === 'jpg' ? 'jpeg' : $format] + $this->encodingOptions());
        return $this;
    }

    private function flags(int $mode): int
    {
        return $mode | (!empty($this->options['resizeUp']) ? ImageInterface::THUMBNAIL_FLAG_UPSCALE : 0);
    }

    private function encodingOptions(): array
    {
        return ['jpeg_quality' => $this->options['jpegQuality'] ?? 90];
    }
}
