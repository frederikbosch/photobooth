<?php

final class PhotoCreator
{

    public function merge (string $outputDirectory, string $canvasFile, array $images)
    {
        if (count($images) !== 4) {
            throw new \InvalidArgumentException('Incorrect number of pictures');
        }

        /* Set width and height in proportion of genuine PHP logo */
        $width = 1181;
        $height = 1772;
        $boundaryX = 100;
        $boundaryY = 200;
        $boundaryMiddle = 100;
        $photoWidth = ($width - $boundaryX * 2) / 2 - $boundaryMiddle;
        $photoHeight = ($height - $boundaryY * 2) / 2 - $boundaryMiddle;

        $xy = [
            [$boundaryX, $boundaryY],
            [$boundaryX + $photoWidth + $boundaryMiddle, $boundaryY],
            [$boundaryX, $boundaryY + $photoHeight + $boundaryMiddle],
            [$boundaryX + $photoWidth + $boundaryMiddle, $boundaryY + $photoHeight + $boundaryMiddle]
        ];

        /* Create an Imagick object with transparent canvas */
        $image = new Imagick();
        $image->newImage($width, $height, new ImagickPixel('transparent'));

        foreach ($images as $key => $photoUrl) {
            $photo = new \Imagick();
            //$photo->readImageBlob(file_get_contents($photoUrl));
            $photo->readImageBlob(
                file_get_contents(__DIR__ . '/../../test/picture.jpg')
            );
            $photo->resizeImage(
                $photoWidth,
                $photoHeight,
                \Imagick::FILTER_CATROM,
                1
            );

            list($x, $y) = $xy[$key];
            $image->compositeImage(
                $photo,
                Imagick::COMPOSITE_ADD,
                $x,
                $y
            );
        }

        $canvas = new \Imagick($canvasFile);

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