<?php

final class MergedPhotoCreator
{

    public function merge (string $outputDirectory, array $images)
    {
        $config = require __DIR__ .'/../../../assets/config.php';

        if (count($images) !== 4) {
            throw new \InvalidArgumentException('Incorrect number of pictures');
        }

        $image = new Imagick();
        $image->newImage(
            PhotoConstant::WIDTH,
            PhotoConstant::HEIGHT,
            new ImagickPixel($config['background'])
        );

        foreach ($images as $key => $photoUrl) {
            $photo = new \Imagick();
            $photo->readImageBlob(file_get_contents($outputDirectory  . '/' . $photoUrl));

            switch ($key) {
                case 0:
                    $x = 0;
                    $y = 0;
                    break;
                case 1:
                    $x = PhotoConstant::PHOTO_WIDTH + 1;
                    $y = 0;
                    break;
                case 2:
                    $x = 0;
                    $y = PhotoConstant::HEIGHT - $photo->getImageHeight();
                    break;
                case 3:
                    $x = PhotoConstant::PHOTO_WIDTH + 1;
                    $y = PhotoConstant::HEIGHT - $photo->getImageHeight();
                    break;
                default:
                    throw new \RuntimeException('Cannot determine x/y of non existing image');
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



}