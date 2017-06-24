<?php

/**
 * Class FetchResponse
 */
class FetchResponse
{
    /**
     * @var string
     */
    private $fileName;

    /**
     * FetchResponse constructor.
     * @param string $fileName
     */
    public function __construct(string $fileName) {
        $this->fileName = $fileName;
    }

    /**
     * @return void
     */
    public function output() {
        header('Content-Type: application/json');
        echo json_encode([
            'fileName' => $this->fileName
        ]);
    }

}