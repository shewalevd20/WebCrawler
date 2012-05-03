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
define("DEFAULT_SEED", "http://www.theage.com.au/digital-life/mobiles/Mobiles");
define("DEFAULT_POLITENESS", 30);
define("URL_OCCURRENCE_WEIGHT", 0.5);
define("ARTICLE_THRESHOLD", 0.625);
define("OCCURRENCE_THRESHOLD", 10);
define("BASE_URL", 'http://localhost:8888/WebCrawler/');
define("RELEVANT_SECTION", 'mobiles');
define("REL_PAGES_FOLDER", 'data/pages/relevant/');
define("IRR_PAGES_FOLDER", 'data/pages/irrelevant/');
define("PAGE_NAME_PREFIX", 'url_');
define("WORDS_AMNT", 10);

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
    private $train;

    // Main WebCrawler Class constructor
    function __construct($politeness = DEFAULT_POLITENESS, $maxpages = MAX_PAGES, $seed_url = DEFAULT_SEED, $train = TRUE, $all_keywords = array()) {
        $this->politeness = $politeness;
        $this->maxpages = $maxpages;
        $this->seed_url = $seed_url;
        $this->host = $this->getHost();
        $this->pages_counter = 0;
        $this->train = $train;
        if (!$this->train)
            $this->all_keywords = $all_keywords;
    }

    public function start() {


        // Comment the following line if you want to clean data/pages directory
        self::makeDataCleanUp();

        $this->start_time = time();
        $this->crawl_dfs($this->seed_url);
        foreach ($this->visitedPages as $article) {
            $page_keywords = $article->extractPopularWords();
            if ($this->train)
                $this->addToAllKeywords($page_keywords);
        }

        if ($this->train) {
            arsort($this->all_keywords);
            $this->all_keywords = array_slice($this->all_keywords, 0, WORDS_AMNT);
        }
        $this->generateWekaFile();

        print_r("\nTotal fetched: " . count($this->visitedPages) . " pages.");
    }

    // DFS based crawler function
    private function crawl_dfs($url) {
        if ((count($this->visitedPages) < $this->maxpages) && ((time() - $this->start_time) <= $this->politeness)) {
            $page = new WebPage($url, $this->host);
            $page->checkArticleTopic();
            $this->visitedPages[] = $page;
            $this->visitedLinks[] = $url;
            $this->pages_counter++;

            print_r("\nFetching: " . $url);
            if ($this->train)
                $page->fetchPage($this->pages_counter);

            foreach ($page->getAllPageLinks() as $link) {
                if (!in_array($link, $this->visitedLinks)) {
                    $this->crawl_dfs($link);
                }
            }
        }
    }

    private function getHost() {
        $pizza = $this->seed_url;
        $pieces = explode("/", $pizza);
        echo ("Host: " . $pieces[2] . "\n");
        return $pieces[2];
    }

    public function getVisitedPages() {
        return $this->visitedPages;
    }

    public static function makeDataCleanUp() {
        $mask_relevant = REL_PAGES_FOLDER . "url_*";
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
                $file_content .= $page->getUrl() . ',' . ($page->isMobileArticle() ? '1' : '0');
            } else {
                $file_content .= "\n" . $page->getUrl() . ',' . ($page->isMobileArticle() ? '1' : '0');
            }
            $counter++;
        }
        file_put_contents($filename, $file_content);
        print_r("Links file generated ('" . $filename . "')\n");
    }

    public function addToAllKeywords($page_keywords) {
        $numPages = count($this->visitedPages);
        foreach ($page_keywords as $keyword => $occurrence) {
            if (!array_key_exists($keyword, $this->all_keywords)) {//$key == -1) {
                $this->all_keywords[$keyword] = $occurrence;
            } else {
                $this->all_keywords[$keyword] += $occurrence;
            }
        }

        $numPages = count($this->visitedPages);
        foreach ($this->all_keywords as $key => $value) {
            $this->all_keywords[$key] = $value / $numPages;
        }
    }

    public function generateWekaFile() {
        $lineStr = "@RELATION Articles \n";
        foreach ($this->all_keywords as $key => $value) {
            $lineStr .= "@ATTRIBUTE {$key} NUMERIC \n";
        }
        $lineStr .= "@ATTRIBUTE class {Mobile,Not-Mobile}\n";
        $lineStr .= "@DATA";

        foreach ($this->visitedPages as $page) {
            $lineStr .= "\n";
            foreach ($this->all_keywords as $key => $value) {
                $occurrence = $page->getPopularWords($key);

                if (isset($occurrence) && trim($occurrence) != '') {
                    $str = $occurrence;
                } else {
                    $str = 0;
                }
                $lineStr .= $str . ",";
            }
            if ($this->train) {
                $lineStr .= ($page->isRelevant() ? "Mobile" : "Not-Mobile");
            } else {
                $lineStr .= "?";
            }
        }

        if ($this->train) {
            file_put_contents("data/articles.arff", $lineStr);
        } else {
            file_put_contents("data/unlabeled.arff", $lineStr);
        }
    }

    public function getAllKeywords() {
        return $this->all_keywords;
    }
    
    

}

?>
