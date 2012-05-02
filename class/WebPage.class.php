<?php

/*
 * RMIT University | School of Computer Science & IT
 * COSC 1165 / 1167 — Intelligent Web Systems 
 * Assignment 2 | Web Crawler and Mining
 * 
 * @author Karim Abulainine  s3314713
 * @author Daniel Stankevich s3336691
 */

define("WORDS_AMNT", 20);

class Word {
    private $word;
    private $occurrence;
    public function __construct($word, $occurrence) {
        $this->word = $word;
        $this->occurrence = $occurrence;
    }
    
    public function getOccurrence(){
        return $this->occurrence;
    }
    
    public function setOccurrence($occurrence){
        $this->occurrence = $occurrence;
    }
    
    public function getWord(){
        return $this->word;
    }
}

class WebPage {
    
    private $id;
    private $url;
    private $content;
    private $host;
    private $visited;
    private $type;
    private $mobile_article;
    private $relevant;
    private $plainText;
    
    private $popular_words = array();
    private $linkedPages = array();
    
    private static $keywords = array("mobile" => array("text" => 0, "url" => 0, "weight" => 0.125),
        "android" => array("text" => 0, "url" => 0, "weight" => 0.125),
        "ios" => array("text" => 0, "url" => 0, "weight" => 0.125),
        "phone" => array("text" => 0, "url" => 0, "weight" => 0.125));

    // Main WebPage Class constructor
    public function __construct($url, $host) {
        $this->url = $url;
        $this->host = $host;
        $this->visited = false;
        $this->relevant = false;        
        $this->content = file_get_contents($this->url);
    }

    public function fetchPage($id) {
        $this->categorizePage();
        $this->id = $id;
        $output_filename = "";
        
        // Determine output folder
        if ($this->relevant)
            $output_filename = REL_PAGES_FOLDER . PAGE_NAME_PREFIX . $this->id;
        else
            $output_filename = IRR_PAGES_FOLDER . PAGE_NAME_PREFIX . $this->id;
        
        // Write to file
        $file_content = "<!-- URL: " . $this->url . " -->\n" . $this->content;
        file_put_contents($output_filename, $file_content);
        print_r("\nPage saved to: " . $output_filename . "\n");
    }

    public function getAllPageLinks() {        
        $anchors = $this->getAllAnchors($this->content);
        foreach ($anchors as $anchor) { 
            $href = $anchor;
            if (substr($href, 0, 4) == "http") {
                if ($this->sameHost($href)) {
                    $href = $this->checkAnchors($href);
                    array_push($this->linkedPages, $href);
                }
            }
        }
        $this->linkedPages = array_unique($this->linkedPages);
        return $this->linkedPages;
    }
    
    private function getAllAnchors($html) {
        $anchors = array();
        for ($i=0; $i < strlen($html); $i++) {
            if ($html[$i] == '<' && $html[$i+1] == 'a') {
                $href = "";
                while (substr($html, $i++, 4) != "href");
                while ($html[$i] != "\'" && $html[$i] != "\"") $i++;
                $i++;
                while ($html[$i] != "\'" && $html[$i] != "\"")
                   $href .= $html[$i++];
                $anchors[] = $href;
            }       
        }
        return $anchors;
    }
    
    private function sameHost($url) {
        $pizza = $url;
        $pieces = explode("/", $pizza);
        $host = $pieces[2];
        return ($host == $this->host);
    }

    private function checkAnchors($href) {
        $cleanHref = explode("#", $href);
        //var_dump($cleanHref);
        if ($cleanHref)
            return $cleanHref[0];
        else
            return $href;
    }

    private function categorizePage() {
        $pizza = $this->url;
        $pieces = explode("/", $pizza);
        $section = $pieces[2];
        foreach ($pieces as $piece) {
            if ($piece == RELEVANT_SECTION) {
                $this->relevant = true;
                return;
            }
        }
    }
    
    // Accessors
    public function getUrl() {
        return $this->url;
    }

    public function getHost() {
        return $this->host;
    }

    public function getType() {
        return $this->type;
    }

    public function isVisited() {
        return $this->visited;
    }

    public function isMobileArticle() {
        return $this->mobile_article;
    }

    public function setVisited() {
        $this->visited = true;
    }

    // Code to check whether the page is a mobile page or not 
    // ** still needs a lot of modifications **
    public function checkArticleTopic() {
        $this->plainText = strip_tags(file_get_contents($this->url)); //file_get_html($this->url)->plaintext;
        $weight = 0;
        $inURL = FALSE;
        foreach (self::$keywords as $key => $value) {
            $value["text"] = substr_count(strtolower($this->plainText), $key);
            $value["url"] = substr_count(strtolower($this->url), $key);

            if ($value["text"] > OCCURRENCE_THRESHOLD) {
                $weight += $value['weight'];
            }

            if ($value['url'] > 0) {
                $inURL = TRUE;
            }

            self::$keywords[$key] = $value;
        }
        if ($inURL) {
            $weight += URL_OCCURRENCE_WEIGHT;
        }

        $this->mobile_article = ($weight > ARTICLE_THRESHOLD);
        //print_r("\n\n" . $this->plainText);
    }
    
    public function extractPopularWords() {
        $text = $this->plainText;
        $text = strtolower($text);
        
        $all_words = array();
        $text = preg_replace('/\s\s+\t\t+/', ' ', $text);
        preg_match_all("/[a-z]{3,}/", $text, $all_words);
        if(count($all_words) > 0){
            $all_words = $all_words[0];
        }
                        
        $words = array();
        $added_words = array();
        foreach ($all_words as $word_out) {
            $count = 0;
            if (!in_array($word_out, $added_words)) {
                foreach ($all_words as $word_in)
                    if ($word_out == $word_in) $count++;
                $words[] = new Word ($word_out, $count);
                $added_words[] = $word_out;
            }
        }
        
        usort($words, array("WebPage", "sort"));
        //print_r($words);
        for($i=0; $i<WORDS_AMNT; $i++){
            $this->popular_words[] = $words[$i];
        }     
        
        return $this->popular_words;
    }
    
    //custom sorting function for associative arrays
    function sort($a, $b) {
        if ($a->getOccurrence() == $b->getOccurrence()) {
            return 0;
        }
        return ($a->getOccurrence() > $b->getOccurrence()) ? -1 : 1;
    }
}


?>
