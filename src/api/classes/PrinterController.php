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

        $this->shellAccess->send(
            sprintf(
                'rundll32 C:\WINDOWS\system32\shimgvw.dll,ImageView_PrintTo %s "%s"',
                $fileName,
                'HiTi'
            )
        );

        return new Response($fileName);
    }

}