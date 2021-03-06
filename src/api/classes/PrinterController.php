<?php

/**
 * Class PrinterController
 */
final class PrinterController
{

    /**
     * @var ShellAccess
     */
    private $shellAccess;

    /**
     * @var MergedPhotoCreator
     */
    private $photoCreator;
    /**
     * @var bool
     */
    private $debug = false;

    /**
     * PrinterController constructor.
     * @param ShellAccess $shellAccess
     * @param MergedPhotoCreator $photoCreator
     * @param bool $debug
     */
    public function __construct(ShellAccess $shellAccess, MergedPhotoCreator $photoCreator, $debug)
    {
        $this->shellAccess = $shellAccess;
        $this->photoCreator = $photoCreator;
        $this->debug = $debug;
    }

    /**
     * @param Request $request
     * @return PrinterResponse
     */
    public function print(Request $request)
    {
        $config = require __DIR__ .'/../../../assets/config.php';

        $fileName = $this->photoCreator->merge(
            __DIR__ . '/../../../data',
            [
                $request->get('photo1'),
                $request->get('photo2'),
                $request->get('photo3'),
                $request->get('photo4'),
            ]
        );

        if ($this->debug === false) {
            $command = sprintf(
                "/usr/bin/scp %s %s:~ && /usr/bin/ssh %s './print.sh %s'",
                $fileName,
                $config['printer'],
                $config['printer'],
                basename($fileName)
            );

            $this->shellAccess->send($command);
        }

        return new PrinterResponse($fileName);
    }

}