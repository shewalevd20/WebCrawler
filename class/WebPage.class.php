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

}

?>
