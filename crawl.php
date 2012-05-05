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

define("FEEDBACK", true);
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
if ($cli["training"] == 'true') {
    $trainer = new WebCrawler(TRAINING_POLITENESS, TRAINING_PAGES, TRAINING_REL_SEED);
    echo "\nTraining...\n";
    WebCrawler::makeDataCleanUp();
    $trainer->start();
    $trainer->setSeedUrl(TRAINING_IRR_SEED);
    $trainer->setHost($trainer->getHost());
    $trainer->setPagesCounter(0);
    $trainer->start();
    $trainer->generateWekaFile();
    echo "\n\nSystem Trained.\n\n";
    
    $keywords = array();
    foreach($trainer->getAllKeywords() as $keyword=>$value){
        $keywords[] = $keyword;
    }
    file_put_contents("data/keywords.txt", implode(",", $keywords));
}

if($cli["training"] != 'true'){
    $keywords = file_get_contents("data/keywords.txt");
    $keywords = explode(",", $keywords);
    $assoc_keywords = array();
    foreach($keywords as $keyword){
        $assoc_keywords[$keyword] = 0;
    }
}else{
    $assoc_keywords = $trainer->getAllKeywords();
}

// CRAWLING
$crawler = new WebCrawler($cli["politeness"], $cli["maxpages"], $cli["seed_url"], FALSE, $assoc_keywords);
echo "\nCrawler started...\n";
$crawler->start();
echo "\n\nCrawler finished.\n\n";



if (WEKA_READER) {
    exec("javac -cp $" . "CLASSPATH:WekaReaderApp/weka.jar WekaReaderApp/WekaReader.java");
    exec("java -cp $" . "CLASSPATH:WekaReaderApp/weka.jar WekaReaderApp/WekaReader");
    echo "\nWeka file generated and classified.\n";
}

// read links and classify them from labeled.arff
$crawler->classifyArticles("data/labeled.arff");

// Launch some goodies
if (GENERATE_LINKS_CSV) {
    $pages = $crawler->getVisitedPages();
    $crawler->writeToFile("data/links.csv", $pages);
}

// display links in index.php
if (FEEDBACK)
    exec("open " . BASE_URL . "index.php");

// add user feedback to articles.arff
// run training again

echo "\nPROGRAM FINISHED.\n";
?>
