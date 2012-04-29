<?php

/**
 * WebPage Class representation
 *
 * @author Daniel Stankevich
 * @author Karim Ainine
 */
class WebPage {

    private $url;
    private $content;
    private $host;
    private $visited;
    private $type;
    private $mobile_article;
    private $linkedPages = array();
    
    function __construct($url, $host) {
        $this->url = $url;
        $this->host = $host;
    }

    function fetchPage() {
        $this->content = file_get_contents($this->url, false, $context);
        $this->visited = true;
        
        // Code to check whether the page is a mobile page or not 
        // ** still needs a lot of modifications **
        
        $plainText = file_get_html($this->url)->plaintext;
        $textPos = strpos(strtolower($plainText), "mobile");
        $urlPos = strpos(strtolower($this->url), "mobile");
        if($textPos !== FALSE && $urlPos !== FALSE){
            $this->mobile_article = TRUE;
        }else{
            $this->mobile_article = FALSE;
        }

        // end of mobile checking

        return $this->content;
    }

    public function getAllPageLinks() {
        $html = file_get_html($this->url);
        $anchors = $html->find('a');
        foreach ($anchors as $anchor) {
            $href = $anchor->href;
            if (substr($href, 0, 4) == "http") {
                if ($this->sameHost($href)){
                    $href = $this->checkAnchors($href);
                    array_push($this->linkedPages, $href);
                }
                
            }
        }
        $this->linkedPages = array_unique($this->linkedPages);
        
        return $this->linkedPages;
    }
    
    private function sameHost($url) {
        $pizza = $url;
        $pieces = explode("/", $pizza);
        $host = $pieces[2];
        //echo ("\nLink Host: " . $host . " Host: " . $this->host . "\n");
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
        return $this->mobile;
    }
}

?>
