<?php

final class Request
{

    private $data;

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function has(string $name): bool {
        return isset($this->data[$name]);
    }

    public function get(string $name): string {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }

        throw new \UnexpectedValueException('Unknown key ' . $name);
    }
}