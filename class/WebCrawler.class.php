<?php

/**
 * WebCrawler Class representation
 *
 * @author Daniel Stankevich
 * @author Karim Ainine
 */
require_once 'WebPage.class.php';
require_once 'inc/simple_html_dom.php';

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
    private $host;
    
    private $pages = array();
    private $visitedPages = array();
    private $visitedLinks = array();
    

    function __construct($politeness = DEFAULT_POLITENESS, $maxpages = MAX_PAGES, $seed_url = DEFAULT_SEED) {
        $this->politeness = $politeness;
        $this->maxpages = $maxpages;
        $this->seed_url = $seed_url;
        $this->host = $this->getHost();
    }

    public function start() {
        $this->crawl($this->seed_url);
    }
    
    private function crawl($url) {
        echo "\nCrawling inside: " . $url . " Visited:" . count($this->visitedPages) . " Max: " . $this->maxpages . "\n" ;
        if ((count($this->visitedPages) < $this->maxpages))
        {
            $page = new WebPage($url, $this->host);
            $page->checkArticleTopic();
            $this->visitedPages[] = $page;
            $this->visitedLinks[] = $url;
            
            foreach ($page->getAllPageLinks() as $link) {
                if (!in_array($link, $this->visitedLinks)){
                    $this->crawl($link);
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
    
    public static function writeToFile($filename, $pages) {
        $file_content = "";
        $counter = 0;
        foreach ($pages as $page) {
            if ($counter == 0) {
                $file_content .= $page->getUrl() . ',' . $page->isMobileArticle();
            } else {
                $file_content .= "\n" . $page->getUrl() . ',' . $page->isMobileArticle();
            }
            $counter++;
        }
        file_put_contents($filename, $file_content);
    }
}

?>
