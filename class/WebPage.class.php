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
    
    private $keywords = array(  "mobile"=>array("text"=>0, "url"=>0, "weight"=>0.125), 
                                "android"=>array("text"=>0, "url"=>0, "weight"=>0.125),
                                "ios"=>array("text"=>0, "url"=>0, "weight"=>0.125),
                                "phone"=>array("text"=>0, "url"=>0, "weight"=>0.125));

    function __construct($url, $host) {
        $this->url = $url;
        $this->host = $host;
        $this->visited = false;
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
        $this->mobile_article = $this->checkArticleTopic();
        print_r($this->mobile_article?'Is Mobile' : 'Not Mobile');

        return $this->content;
    }

    public function getAllPageLinks() {
        echo $this->url . "\n";
        $html = file_get_html($this->url);
        echo "\nGET_ALL_LINKS\n";
        $anchors = $html->find('a');
        echo "Anchors: " . count($anchors) . "\n";        
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
    
    public function setVisited() {
        $this->visited = true;
    }
    
    // Code to check whether the page is a mobile page or not 
    // ** still needs a lot of modifications **
    private function checkArticleTopic(){
        
        $plainText = file_get_html($this->url)->plaintext;
        $weight = 0;
        $inURL = FALSE;
        foreach($this->keywords as $key=>$value){
            $value["text"] = substr_count(strtolower($plainText), $key);
            $value["url"] = substr_count(strtolower($this->url), $key);
            
            if($value["text"] > OCCURRENCE_THRESHOLD){
                $weight += $value['weight'];
            }
            
            if($value['url']>0){
                $inURL = TRUE;
            }
            
            $this->keywords[$key] = $value;
        }
        print_r($this->keywords);
        if($inURL){
            $weight += URL_OCCURRENCE_WEIGHT;
        }
        
        print_r("\n{$weight}\n");
        
        return ($weight > ARTICLE_THRESHOLD);
    }
    // end of mobile checking
}

?>
