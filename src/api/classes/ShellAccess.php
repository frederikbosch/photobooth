<?php

final class ShellAccess
{

    public function send($command)
    {
        shell_exec($command);
    }

}