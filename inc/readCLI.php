<?php

require_once 'class/exceptions/CLIException.class.php';

function readCLI() {
    $shortopts = "h";
    $longopts = array(
        "politeness::",
        "maxpages::",
        "seed_url:"
    );
    $options = getopt($shortopts, $longopts);
    $cli = array("politeness" => $options["politeness"],
        "maxpages" => $options["maxpages"],
        "seed_url" => $options["seed_url"]);
    if (!isset($cli['seed_url'])) {
        throw new CLIException("You must specify 'seed_url' in order to start crawling (Usage: '--seed_url='VALUE')\n");
    }        
    var_dump($cli);
    return $cli;
}

?>
