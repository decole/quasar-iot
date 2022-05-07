<?php


namespace Decole\Quasar\Exception;


use Exception;

class RussianWordException extends Exception
{
    protected $message = "configure config/quasariot.php 'commandName' to russian word, for Example 'Голос'";

    public function __toString() {
        return __CLASS__ . ": {$this->message}\n";
    }
}
