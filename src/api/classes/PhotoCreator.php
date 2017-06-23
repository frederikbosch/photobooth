<?php

final class PhotoCreator
{

    public function merge (string $outputDirectory, string $canvasFile, array $images)
    {
        $config = require __DIR__ .'/../../../assets/config.php';

        if (count($images) !== 4) {
            throw new \InvalidArgumentException('Incorrect number of pictures');
        }

        $width = 1181;
        $height = 1772;
        $photoWidth = ($width - 1) / 2;

        $image = new Imagick();
        $image->newImage($width, $height, new ImagickPixel($config['background']));

        foreach ($images as $key => $photoUrl) {
            $photo = new \Imagick();
            $photo->readImageBlob(file_get_contents($photoUrl));
            $this->orientate($photo);

            $photo->resizeImage(
                $photoWidth,
                null,
                \Imagick::FILTER_CATROM,
                1
            );

            switch ($key) {
                case 0:
                    $x = 0;
                    $y = 0;
                    break;
                case 1:
                    $x = $photoWidth + 1;
                    $y = 0;
                    break;
                case 2:
                    $x = 0;
                    $y = $height - $photo->getImageHeight();
                    break;
                case 3:
                    $x = $photoWidth + 1;
                    $y = $height - $photo->getImageHeight();
                    break;
            }

            $image->compositeImage(
                $photo,
                Imagick::COMPOSITE_ADD,
                $x,
                $y
            );
        }

        $image->setImageUnits(\Imagick::RESOLUTION_PIXELSPERINCH);
        $image->setImageResolution(300, 300);

        $canvas = new \Imagick($config['overlay']);

        $image->compositeImage(
            $canvas,
            Imagick::COMPOSITE_ADD,
            0,
            0
        );

        $image->setFormat('JPG');

        $fileName = $outputDirectory . '/' . (new DateTimeImmutable('now'))->format('Y-m-d-H-i-s') . '.jpg';
        $image->writeImage($fileName);

        return realpath($fileName);
    }

    private function orientate(\Imagick $photo)
    {
        $orientation = $photo->getImageProperty('exif:Orientation');
        $photo->rotateImage(new \ImagickPixel('#00000000'), (90 * -1));
    }

}