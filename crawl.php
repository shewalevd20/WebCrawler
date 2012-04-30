<?php


/**
 * RMIT
 * @author Daniel Stankevich
 * @author Karim Ainine
 */

include_once 'inc/readCLI.php';
include_once 'inc/print_help.php';
include_once 'inc/simple_html_dom.php';
include_once 'inc/generate_weka_file.php';
require_once 'class/WebPage.class.php';
require_once 'class/WebCrawler.class.php';

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
echo "Crawler finished.\n\n";

//$pages = $crawler->getVisitedPages();
//WebCrawler::writeToFile("links", $pages);
//generateWekaFile();
//exec("open " . BASE_URL . "index.php");

?>
