<?php

final class PrinterController {

    private $shellAccess;

    private $photoCreator;
    
    public function __construct(ShellAccess $shellAccess, PhotoCreator $photoCreator)
    {
        $this->shellAccess = $shellAccess;
        $this->photoCreator = $photoCreator;
    }

    public function print(Request $request)
    {
        $fileName = $this->photoCreator->merge(
            __DIR__ . '/../../../data',
            __DIR__ . '/../../../assets/canvas.png',
            [
                $request->get('photo1'),
                $request->get('photo2'),
                $request->get('photo3'),
                $request->get('photo4'),
            ]
        );

        $this->shellAccess->send('lpr -o ppi=300 -o document-format=image/jpeg ' . $fileName);
        return new Response($fileName);
    }

}