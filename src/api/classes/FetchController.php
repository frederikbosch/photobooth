<?php

/**
 * Class FetchController
 */
final class FetchController
{
    /**
     * @var bool
     */
    private $debug = false;

    /**
     * FetchController constructor.
     * @param bool $debug
     */
    public function __construct(bool $debug)
    {
        $this->debug = $debug;
    }

    /**
     * @return FetchResponse
     */
    public function fetch(): FetchResponse
    {
        if ($this->debug === false) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://10.5.5.9:8080/videos/DCIM/100GOPRO/');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_INTERFACE, '10.5.5.109');
            $photoList = curl_exec($ch);

            $dom = new \DOMDocument();
            $dom->loadHTML($photoList);
            $links = $dom->getElementsByTagName('a');

            $lastLink = $links->item($links->length - 1);
            return new FetchResponse($lastLink->getAttribute('href'));
        } else {
            return new FetchResponse('debug.jpg');
        }
    }
}