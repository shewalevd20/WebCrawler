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
define("GENERATE_ARFF", false);
define("GENERATE_LINKS_CSV", true);
define("WEKA_READER", true);

echo "\nPROGRAM STARTED.\n\n";

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

// TRAINING
$trainer = new WebCrawler();
echo "\nTraining...\n";
$trainer->start();
echo "\n\nSystem Trained.\n\n";

// CRAWLING
$crawler = new WebCrawler($cli["politeness"], $cli["maxpages"], $cli["seed_url"], FALSE, $trainer->getAllKeywords());
echo "\nCrawler started...\n";
$crawler->start();
echo "\n\nCrawler finished.\n\n";

// Launch some goodies
if (GENERATE_LINKS_CSV){
    $pages = $crawler->getVisitedPages();
    WebCrawler::writeToFile("data/links.csv", $pages);
}
if (GENERATE_ARFF) $crawler->generateWekaFile();
if (WEKA_READER) {
    exec("javac -cp $"."CLASSPATH:WekaReaderApp/weka.jar WekaReaderApp/WekaReader.java");
    exec("java -cp $"."CLASSPATH:WekaReaderApp/weka.jar WekaReaderApp/WekaReader");
    echo "\nWeka file generated and classified.\n";
}

// read links and classify them from labeled.arff

// display links in index.php
if (FEEDBACK) exec("open " . BASE_URL . "index.php");

// add user feedback to articles.arff

// run training again

echo "\nPROGRAM FINISHED.\n";
?>
