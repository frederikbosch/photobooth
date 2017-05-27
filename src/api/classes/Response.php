<?php

final class Response implements \JsonSerializable
{
    private $fileName;

    public function __construct(string $fileName) {
        $this->fileName = $fileName;
    }

    public function jsonSerialize() {
        return [
            'fileName' => $this->fileName
        ];
    }
}