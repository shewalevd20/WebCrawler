<?php

class CLIException extends Exception {

    function __construct($msg) {
        $this->message = "\nCLI Format Error: ";
        $this->message .= $msg;
    }

}

?>
