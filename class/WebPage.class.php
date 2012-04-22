<?php


/**
 * WebPage Class representation
 *
 * @author Daniel Stankevich
 * @author Karim Ainine
 */
class WebPage {

    private $url;
    private $host;
    private $visited;
    private $type;
    
    function WebPage($url, $host) {
        $this->url = $url;
        $this->host = $host;
    }
    
    function openUrl() {
        
    }
    
    function fetchPage() {
        
    }
    
    public function getUrl(){return $this->url;}
    public function getHost(){return $this->host;}
    public function getType(){return $this->type;}
    public function isVisited(){return $this->visited;}
        
}

?>
