<?php

/*
 * RMIT University | School of Computer Science & IT
 * COSC 1165 / 1167 — Intelligent Web Systems 
 * Assignment 2 | Web Crawler and Mining
 * 
 * @author Karim Abulainine  s3314713
 * @author Daniel Stankevich s3336691
 */

require_once 'WebPage.class.php';
require_once 'inc/simple_html_dom.php';

// Crawler constants
define("MAX_PAGES", 20);
define("DEFAULT_SEED", "http://www.theage.com.au/digital-life/");
define("DEFAULT_POLITENESS", 30);
define("URL_OCCURRENCE_WEIGHT",0.5);
define("ARTICLE_THRESHOLD",0.625);
define("OCCURRENCE_THRESHOLD",10);
define("BASE_URL",'http://localhost:8888/WebCrawler/');

class WebCrawler {

    private $politeness;
    private $maxpages;
    private $seed_url;
    private $host;
    
    private $pages = array();
    private $visitedPages = array();
    private $visitedLinks = array();
    
    private $start_time;

    // Main WebCrawler Class constructor
    function __construct($politeness = DEFAULT_POLITENESS, $maxpages = MAX_PAGES, $seed_url = DEFAULT_SEED) {
        $this->politeness = $politeness;
        $this->maxpages = $maxpages;
        $this->seed_url = $seed_url;
        $this->host = $this->getHost();
    }

    public function start() {
        $this->start_time = time();
        $this->crawl_dfs($this->seed_url);
        print_r("\nTotal fetched: " . count($this->visitedPages) . " pages.");
    }
    
    // DFS based crawler function
    private function crawl_dfs($url) {
        $currentTime = time();
        if ((count($this->visitedPages) < $this->maxpages) && ((time() - $this->start_time) <= $this->politeness))
        {
            $page = new WebPage($url, $this->host);
            $page->checkArticleTopic();
            $this->visitedPages[] = $page;
            $this->visitedLinks[] = $url;
            
            print_r("\nFetching: " . $url);
            
            foreach ($page->getAllPageLinks() as $link) {
                if (!in_array($link, $this->visitedLinks)){
                    $this->crawl_dfs($link);
                }
            }
        }      
    }

    private function getHost(){
        $pizza = $this->seed_url;
        $pieces = explode("/", $pizza);
        echo ("Host: " . $pieces[2] . "\n");
        return $pieces[2];
    }

    public function addPage($webPage) {
        array_push($this->pages, $webPage);
    }

    public function getVisitedPages() {
        return $this->visitedPages;
    }
    
    /* Static functions */
    
    public static function writeToFile($filename, $pages) {
        $file_content = "";
        $counter = 0;
        foreach ($pages as $page) {
            if ($counter == 0) {
                $file_content .= $page->getUrl() . ',' . ($page->isMobileArticle()?'1':'0');
            } else {
                $file_content .= "\n" . $page->getUrl() . ',' . ($page->isMobileArticle()?'1':'0');
            }
            $counter++;
        }
        file_put_contents($filename, $file_content);
        print_r("Links file generated ('" . $filename . "')\n");
    }
}

?>
