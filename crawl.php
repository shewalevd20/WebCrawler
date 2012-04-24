<?php

include_once 'inc/readCLI.php';
include_once 'inc/print_help.php';
include_once('inc/simple_html_dom.php');
require_once 'class/WebPage.class.php';
require_once 'class/WebCrawler.class.php';

$cli = array();
try {
    $cli = readCLI();
    if (isset($cli["help"]))
        print_help();
} catch (NOSeedUrlException $exc) {
    print_r($exc->getMessage());
    print_help();
    die();
}

// Create new crawler (pass cli into that)
$crawler = new WebCrawler($cli["politeness"], $cli["maxpages"], $cli["seed_url"]);

$page = new WebPage($cli["seed_url"], "");
$page->fetchPage();

// Start crawling here
echo "Crawler started...\n";

echo "Crawler finished.\n";
?>
