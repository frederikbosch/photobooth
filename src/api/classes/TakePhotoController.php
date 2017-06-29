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
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://10.5.5.9/bacpac/SH?t=17171410&p=%01');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_INTERFACE, '10.5.5.109');
            $output = curl_exec($ch);
        }

        return new TakePhotoResponse();
    }

}