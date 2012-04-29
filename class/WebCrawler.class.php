<?php

/**
 * WebCrawler Class representation
 *
 * @author Daniel Stankevich
 * @author Karim Ainine
 */
require_once 'WebPage.class.php';

// Defines
define("MAX_PAGES", 20);
define("DEFAULT_SEED", "");
define("DEFAULT_POLITENESS", 30);
define("URL_OCCURRENCE_WEIGHT",0.5);
define("ARTICLE_THRESHOLD",0.625);
define("OCCURRENCE_THRESHOLD",10);
define("BASE_URL",'http://localhost:8888/WebCrawler/');

class WebCrawler {

    private $politeness;
    private $maxpages;
    private $seed_url;
    private $pages = array();

    function __construct($politeness = DEFAULT_POLITENESS, $maxpages = MAX_PAGES, $seed_url = DEFAULT_SEED) {
        $this->politeness = $politeness;
        $this->maxpages = $maxpages;
        $this->seed_url = $seed_url;
    }

    public function crawl($seed_url) {
        $seed_url = $this->seed_url;
        
    }

    public function addPage($webPage) {
        array_push($this->pages, $webPage);
    }

    public function getAllPages() {
        return $this->pages;
    }

    public function getVisitedPages() {
        $visitedPages = array();
        // TODO
        return $visitedPages;
    }

    public function getUnVisitedPages() {
        $unVisitedPages = array();
        // TODO
        return $unVisitedPages;
    }

    public function getPageByIndex($index) {
        return $this->pages[$index];
    }
}

?>
