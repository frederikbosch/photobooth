<?php
class PhotoResponse
{
    /**
     * @var string
     */
    private $fileName;

    /**
     * PhotoResponse constructor.
     * @param string $fileName
     */
    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     *
     */
    public function output()
    {
        header('Content-Type: image/jpeg');
        readfile($this->fileName);
    }

}