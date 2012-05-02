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
define("RELEVANT_SECTION", 'mobile');
define("REL_PAGES_FOLDER", 'data/pages/relevant/');
define("IRR_PAGES_FOLDER", 'data/pages/irrelevant/');
define("PAGE_NAME_PREFIX", 'url_');

class WebCrawler {

    private $politeness;
    private $maxpages;
    private $seed_url;
    private $host;
    
    private $pages = array();
    private $visitedPages = array();
    private $visitedLinks = array();
    private $pageNames = array();
    private $start_time;
    private $pages_counter;
    private $all_keywords = array();

    // Main WebCrawler Class constructor
    function __construct($politeness = DEFAULT_POLITENESS, $maxpages = MAX_PAGES, $seed_url = DEFAULT_SEED) {
        $this->politeness = $politeness;
        $this->maxpages = $maxpages;
        $this->seed_url = $seed_url;
        $this->host = $this->getHost();
        $this->pages_counter = 0;
        
    }

    public function start() {
        
        // Comment the following line if you want to clean data/pages directory
        self::makeDataCleanUp();
        
        $this->start_time = time();
        $this->crawl_dfs($this->seed_url);
        foreach ($this->visitedPages as $article) {
            $page_keywords = $article->extractPopularWords();
            foreach ($page_keywords as $keyword){
                $key = array_search($keyword->getWord(), $this->all_keywords);
                if($key == FALSE){
                    $this->all_keywords[] = $keyword;
                }else{
                    if($this->all_keywords[$key]->getOccurrence() < $keyword->getOccurrence()){
                        $this->all_keywords[$key]->setOccurrence( $keyword->getOccurrence());
                    }
                    
                }
            }
        }
        
        usort($this->all_keywords, array("WebPage", "sort"));
        for($i=0; $i<10; $i++){
            print_r(($i+1)." - ");
            print_r($this->all_keywords[$i]);
            print_r("\n");
        } 
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
            $this->pages_counter++;

            print_r("\nFetching: " . $url);
            $page->fetchPage($this->pages_counter);
            
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

    public function getVisitedPages() {
        return $this->visitedPages;
    }

    public static function makeDataCleanUp() {
        $mask_relevant =  REL_PAGES_FOLDER . "url_*";
        $mask_irrelevant = IRR_PAGES_FOLDER . "url_*";
        array_map("unlink", glob($mask_relevant));
        array_map("unlink", glob($mask_irrelevant));
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
