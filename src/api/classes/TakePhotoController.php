<?php
final class TakePhotoController
{
    /**
     * @var bool
     */
    private $debug = false;

    /**
     * TakePhotoController constructor.
     * @param bool $debug
     */
    public function __construct(bool $debug)
    {
        $this->debug = $debug;
    }


    public function take(): TakePhotoResponse
    {
        if ($this->debug === false) {
            file_get_contents('http://10.5.5.9/bacpac/SH?t=17171410&p=%01');
        }

        return new TakePhotoResponse();
    }

}