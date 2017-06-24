<?php
final class TakePhotoResponse
{

    public function output()
    {
        header('Content-Type: application/json');
        echo json_encode(['message' => 'OK']);
    }

}