<?php

require_once 'CLIException.class.php';

class NoSeedUrlException extends CLIException {

    function __construct($msg) {
        $this->message .= $msg;
    }

}

?>
