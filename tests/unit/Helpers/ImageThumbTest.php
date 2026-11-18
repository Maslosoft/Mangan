<?php

declare(strict_types=1);

namespace Helpers;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Helpers\ImageThumb;
use Maslosoft\ManganTest\Extensions\Interfaces\TestDoesNotUseMongoDB;

final class ImageThumbTest extends Unit implements TestDoesNotUseMongoDB
{
    public function testResizePreservesAspectRatioAndTransparency(): void
    {
        $path = $this->createImage();
        try {
            $image = (new ImageThumb($path))->resize(40, 40);
            $this->assertSame(['width' => 40, 'height' => 20], $image->getCurrentDimensions());
            $encoded = imagecreatefromstring($image->getImageAsString());
            $this->assertSame(127, imagecolorsforindex($encoded, imagecolorat($encoded, 0, 0))['alpha']);
            $image->save($path);
            $this->assertSame(IMAGETYPE_PNG, getimagesize($path)[2]);
        } finally {
            unlink($path);
        }
    }

    public function testCropAndSingleDimensionResize(): void
    {
        $path = $this->createImage();
        try {
            $this->assertSame(['width' => 30, 'height' => 30], (new ImageThumb($path))->adaptiveResize(30, 30)->getCurrentDimensions());
            $this->assertSame(['width' => 40, 'height' => 20], (new ImageThumb($path))->resize(0, 20)->getCurrentDimensions());
            $this->assertSame(['width' => 40, 'height' => 20], (new ImageThumb($path))->adaptiveResize(0, 20)->getCurrentDimensions());
            $this->assertSame(['width' => 40, 'height' => 20], (new ImageThumb($path))->adaptiveResize(40, 0)->getCurrentDimensions());
            $this->assertSame(['width' => 80, 'height' => 40], (new ImageThumb($path))->resize(800, 800)->getCurrentDimensions());
            $this->assertSame(['width' => 160, 'height' => 80], (new ImageThumb($path, ['resizeUp' => true]))->resize(0, 80)->getCurrentDimensions());
        } finally {
            unlink($path);
        }
    }

    public function testJpegAndGifCanBeSavedWithoutAnExtension(): void
    {
        foreach (['jpeg', 'gif'] as $format) {
            $path = tempnam(sys_get_temp_dir(), 'mangan-image-test-');
            try {
                $source = imagecreatetruecolor(80, 40);
                ('image' . $format)($source, $path);
                $image = (new ImageThumb($path))->resize(40, 20)->save($path);
                $this->assertSame(['width' => 40, 'height' => 20], (new ImageThumb($path))->getCurrentDimensions());
                $this->assertSame(getimagesize($path)[2], getimagesizefromstring($image->getImageAsString())[2]);
            } finally {
                unlink($path);
            }
        }
    }

    private function createImage(): string
    {
        $path = tempnam(sys_get_temp_dir(), 'mangan-image-test-');
        $image = imagecreatetruecolor(80, 40);
        imagealphablending($image, false);
        imagesavealpha($image, true);
        imagefill($image, 0, 0, imagecolorallocatealpha($image, 0, 0, 0, 127));
        imagepng($image, $path);
        return $path;
    }
}
