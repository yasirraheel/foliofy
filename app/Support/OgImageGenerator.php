<?php

namespace App\Support;

use GdImage;
use Illuminate\Support\Facades\File;

class OgImageGenerator
{
    public function ensureGenerated(): ?string
    {
        $sourcePath = $this->sourcePath();

        if (! File::exists($sourcePath)) {
            return null;
        }

        $outputPath = $this->outputPath();

        if ($this->isFresh($sourcePath, $outputPath)) {
            return $outputPath;
        }

        $source = $this->loadSourceImage($sourcePath);

        if (! $source instanceof GdImage) {
            return null;
        }

        $width = $this->width();
        $height = $this->height();
        $canvas = imagecreatetruecolor($width, $height);

        if (! $canvas instanceof GdImage) {
            imagedestroy($source);

            return null;
        }

        File::ensureDirectoryExists(dirname($outputPath));

        imagealphablending($canvas, true);
        imagesavealpha($canvas, false);

        $background = imagecolorallocate($canvas, 15, 23, 42);
        imagefilledrectangle($canvas, 0, 0, $width, $height, $background);

        $this->drawCoveredImage($canvas, $source, $width, $height);

        for ($index = 0; $index < 4; $index++) {
            imagefilter($canvas, IMG_FILTER_GAUSSIAN_BLUR);
        }

        $overlay = imagecolorallocatealpha($canvas, 6, 14, 28, 52);
        imagefilledrectangle($canvas, 0, 0, $width, $height, $overlay);

        $this->drawContainedImage($canvas, $source, $width, $height);

        imageinterlace($canvas, true);
        $saved = imagejpeg($canvas, $outputPath, 88);

        imagedestroy($canvas);
        imagedestroy($source);

        return $saved && File::exists($outputPath) ? $outputPath : null;
    }

    public function outputPath(): string
    {
        return (string) config('portfolio.og_image_path', storage_path('app/public/portfolio/og-image.jpg'));
    }

    public function sourcePath(): string
    {
        $configured = (string) config('portfolio.profile_image_path', public_path('profile.png'));

        if (File::exists($configured)) {
            return $configured;
        }

        return public_path('profile.png');
    }

    public function width(): int
    {
        return (int) config('portfolio.og_image_width', 1200);
    }

    public function height(): int
    {
        return (int) config('portfolio.og_image_height', 630);
    }

    private function isFresh(string $sourcePath, string $outputPath): bool
    {
        return File::exists($outputPath)
            && File::lastModified($outputPath) >= File::lastModified($sourcePath);
    }

    private function loadSourceImage(string $path): ?GdImage
    {
        $imageInfo = @getimagesize($path);
        $mimeType = $imageInfo['mime'] ?? null;

        if (! is_string($mimeType) || $mimeType === '') {
            return null;
        }

        $image = match ($mimeType) {
            'image/jpeg' => @imagecreatefromjpeg($path),
            'image/png' => @imagecreatefrompng($path),
            'image/gif' => @imagecreatefromgif($path),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : null,
            default => null,
        };

        if (! $image instanceof GdImage) {
            return null;
        }

        imagesavealpha($image, true);

        return $mimeType === 'image/jpeg'
            ? $this->orientJpegImage($image, $path)
            : $image;
    }

    private function orientJpegImage(GdImage $image, string $path): GdImage
    {
        if (! function_exists('exif_read_data')) {
            return $image;
        }

        $exif = @exif_read_data($path);
        $orientation = (int) ($exif['Orientation'] ?? 1);

        $rotated = match ($orientation) {
            3 => imagerotate($image, 180, 0),
            6 => imagerotate($image, -90, 0),
            8 => imagerotate($image, 90, 0),
            default => $image,
        };

        if (! $rotated instanceof GdImage || $rotated === $image) {
            return $image;
        }

        imagedestroy($image);

        return $rotated;
    }

    private function drawCoveredImage(GdImage $canvas, GdImage $source, int $targetWidth, int $targetHeight): void
    {
        $sourceWidth = imagesx($source);
        $sourceHeight = imagesy($source);
        $scale = max($targetWidth / $sourceWidth, $targetHeight / $sourceHeight);
        $drawWidth = (int) ceil($sourceWidth * $scale);
        $drawHeight = (int) ceil($sourceHeight * $scale);
        $drawX = (int) floor(($targetWidth - $drawWidth) / 2);
        $drawY = (int) floor(($targetHeight - $drawHeight) / 2);

        imagecopyresampled(
            $canvas,
            $source,
            $drawX,
            $drawY,
            0,
            0,
            $drawWidth,
            $drawHeight,
            $sourceWidth,
            $sourceHeight
        );
    }

    private function drawContainedImage(GdImage $canvas, GdImage $source, int $targetWidth, int $targetHeight): void
    {
        $sourceWidth = imagesx($source);
        $sourceHeight = imagesy($source);
        $horizontalPadding = 96;
        $verticalPadding = 48;
        $availableWidth = max(1, $targetWidth - ($horizontalPadding * 2));
        $availableHeight = max(1, $targetHeight - ($verticalPadding * 2));
        $scale = min($availableWidth / $sourceWidth, $availableHeight / $sourceHeight);
        $drawWidth = max(1, (int) round($sourceWidth * $scale));
        $drawHeight = max(1, (int) round($sourceHeight * $scale));
        $drawX = (int) floor(($targetWidth - $drawWidth) / 2);
        $drawY = (int) floor(($targetHeight - $drawHeight) / 2);

        $shadow = imagecolorallocatealpha($canvas, 4, 8, 16, 88);
        imagefilledrectangle(
            $canvas,
            $drawX + 18,
            $drawY + 22,
            $drawX + $drawWidth + 18,
            $drawY + $drawHeight + 22,
            $shadow
        );

        $frame = imagecolorallocatealpha($canvas, 255, 255, 255, 28);
        imagefilledrectangle(
            $canvas,
            $drawX - 10,
            $drawY - 10,
            $drawX + $drawWidth + 10,
            $drawY + $drawHeight + 10,
            $frame
        );

        $matte = imagecolorallocate($canvas, 255, 255, 255);
        imagefilledrectangle(
            $canvas,
            $drawX,
            $drawY,
            $drawX + $drawWidth,
            $drawY + $drawHeight,
            $matte
        );

        imagecopyresampled(
            $canvas,
            $source,
            $drawX,
            $drawY,
            0,
            0,
            $drawWidth,
            $drawHeight,
            $sourceWidth,
            $sourceHeight
        );
    }
}
