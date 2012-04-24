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
