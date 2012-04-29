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
    private $visited = array();
    private $links = array();

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
        $newPage = new WebPage($url, $this->host);
        $this->addPage($newPage);   
        $this->visited[] = $newPage->getUrl();
        foreach($newPage->getAllPageLinks() as $link){
            $this->links[] = $link;
        }
        for($i=0; $i<count($links) && count($this->visited) < $this->maxpages; $i++){
            $url = $links[$i];
            if(!in_array($url, $this->visited)){
                $this->crawl($url);
            }
        }
        
        if (count($this->visited) < $this->maxpages)            
        var_dump($this->links);
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

    public static function getVisitedPages() {
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
