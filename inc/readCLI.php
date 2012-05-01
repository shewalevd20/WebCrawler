<?php

/*
 * RMIT University | School of Computer Science & IT
 * COSC 1165 / 1167 — Intelligent Web Systems 
 * Assignment 2 | Web Crawler and Mining
 * 
 * @author Karim Abulainine  s3314713
 * @author Daniel Stankevich s3336691
 */

require_once 'class/exceptions/cli/NoSeedUrlException.class.php';

function readCLI() {
    $shortopts = "";
    $longopts = array(
        "politeness::",
        "maxpages::",
        "seed_url:",
        "help::"
    );
    $options = getopt($shortopts, $longopts);
    $cli = array("politeness" => $options["politeness"],
        "maxpages" => $options["maxpages"],
        "seed_url" => $options["seed_url"],
        "help" => $options["help"]);
    if (!isset($cli['seed_url'])) {
        throw new NOSeedUrlException("You must specify 'seed_url' in order to start crawling (Usage: '--seed_url='VALUE')\n");
    }
    //var_dump($cli);
    return $cli;
}

?>
