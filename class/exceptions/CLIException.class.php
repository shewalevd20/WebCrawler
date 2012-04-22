<?php

class CLIException extends Exception {

    function __construct($msg) {
        $this->message = $msg;
    }

}

?>
