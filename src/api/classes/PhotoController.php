<?php
final class PhotoController {

    /**
     * @var bool
     */
    private $debug;

    /**
     * PhotoController constructor.
     * @param bool $debug
     */
    public function __construct($debug)
    {
        $this->debug = $debug;
    }

    /**
     * @param string $fileName
     * @return PhotoResponse
     */
    public function stream(string $fileName): PhotoResponse
    {
        $outputDirectory = __DIR__ . '/../../../data';

        if ($this->debug === false) {
            $jpeg = file_get_contents('http://10.5.5.9:8080/videos/DCIM/100GOPRO/' . $fileName);

            $photo = new \Imagick();
            $photo->readImageBlob($jpeg);
            $this->orientate($photo);

            $photo->resizeImage(
                PhotoConstant::PHOTO_WIDTH,
                null,
                \Imagick::FILTER_CATROM,
                1
            );

            $outputFile = $outputDirectory . '/' . $fileName;
            $photo->writeImage($outputFile);
        } else {
            $outputFile = $outputDirectory . '/' . $fileName;
        }

        return new PhotoResponse($outputFile);
    }

    /**
     * @param Imagick $photo
     */
    private function orientate(\Imagick $photo)
    {
        $photo->rotateImage(new \ImagickPixel('#00000000'), (90 * -1));
    }

}