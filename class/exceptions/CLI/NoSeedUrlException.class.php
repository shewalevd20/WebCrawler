<?php

/*
 * RMIT University | School of Computer Science & IT
 * COSC 1165 / 1167 — Intelligent Web Systems 
 * Assignment 2 | Web Crawler and Mining
 * 
 * @author Karim Abulainine  s3314713
 * @author Daniel Stankevich s3336691
 */

require_once 'CLIException.class.php';

class NoSeedUrlException extends CLIException {

    function __construct($msg) {
        $this->message .= $msg;
    }
}

?>
