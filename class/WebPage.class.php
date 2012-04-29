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
    }

    function openUrl() {
        
    }

    function fetchPage() {
        $opts = array(
            'http' => array(
                'method' => "GET",
                'type' => "text/html")
        );
        $context = stream_context_create($opts);

        $this->content = file_get_contents($this->url, false, $context);
        $this->visited = true;
        
        $this->mobile_article = $this->checkArticleTopic();
        print_r($this->mobile_article?'Is Mobile' : 'Not Mobile');

        return $this->content;
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
