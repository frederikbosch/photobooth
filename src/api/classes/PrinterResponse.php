<?php

final class PrinterResponse
{
    private $fileName;

    public function __construct(string $fileName) {
        $this->fileName = $fileName;
    }

    public function output() {
        header('Content-Type: application/json');
        echo json_encode([
            'fileName' => $this->fileName
        ]);
    }
}