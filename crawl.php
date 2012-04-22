<?php

include_once 'inc/readCLI.php';
require_once 'class/WebPage.class.php';
require_once 'class/WebCrawler.class.php';

$cli = array();
try {
    $cli = readCLI();
} catch (CLIException $exc) {
    print_r($exc->getMessage());
    die();
}

// Create new crawler (pass cli into that)
$crawler = new WebCrawler($cli["politeness"], $cli["maxpages"], $cli["seed_url"]);


// Start crawling here
echo "Crawler started...\n";

echo "Crawler finished.\n";
?>
