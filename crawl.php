<?php

/*
 * RMIT University | School of Computer Science & IT
 * COSC 1165 / 1167 — Intelligent Web Systems 
 * Assignment 2 | Web Crawler and Mining
 * 
 * @author Karim Abulainine  s3314713
 * @author Daniel Stankevich s3336691
 */

include_once 'inc/readCLI.php';
include_once 'inc/print_help.php';
include_once 'inc/simple_html_dom.php';
include_once 'inc/generate_weka_file.php';
require_once 'class/WebPage.class.php';
require_once 'class/WebCrawler.class.php';

define("FEEDBACK", false);
define("GENERATE_ARRF", true);
define("GENERATE_LINKS_CSV", true);

// Read command lines
$cli = array();
try {
    $cli = readCLI();
    if (isset($cli["help"]))
        print_help();
    if (!isset($cli["maxpages"]))
        $cli["maxpages"] = MAX_PAGES;
    if (!isset($cli["politeness"]))
        $cli["politeness"] = DEFAULT_POLITENESS;
} catch (NOSeedUrlException $exc) {
    print_r($exc->getMessage());
    print_help();
    die();
}

// Create new crawler (pass cli into that)
$crawler = new WebCrawler($cli["politeness"], $cli["maxpages"], $cli["seed_url"]);

//Start crawling here
echo "\nCrawler started...\n";
$crawler->start();
echo "\n\nCrawler finished.\n\n";

// Get all the pages the crawler visited
$pages = $crawler->getVisitedPages();

// Launch some goodies
if (GENERATE_LINKS_CSV) WebCrawler::writeToFile("links.csv", $pages);
if (GENERATE_ARRF) generateWekaFile();
if (FEEDBACK) exec("open " . BASE_URL . "index.php");


?>
