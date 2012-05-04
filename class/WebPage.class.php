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

class WebPage {

    private $id;
    private $url;
    private $content;
    private $host;
    private $visited;
    private $type;
    private $relevant;
    private $plainText;
    private $popular_words = array();
    private $linkedPages = array();

    // Main WebPage Class constructor
    public function __construct($url, $host) {
        $this->url = $url;
        $this->host = $host;
        $this->visited = false;
        $this->relevant = false;
        $this->content = file_get_contents($this->url);
        $this->plainText = strtolower($this->content);
    }

    public function fetchPage($id) {
        $this->categorizePage();
        $this->id = $id;
        $output_filename = "";

        // Determine output folder
        if ($this->relevant)
            $output_filename = REL_PAGES_FOLDER . PAGE_NAME_PREFIX . ($this->id);
        else
            $output_filename = IRR_PAGES_FOLDER . PAGE_NAME_PREFIX . $this->id ;

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
        for ($i = 0; $i < strlen($html); $i++) {
            if ($html[$i] == '<' && $html[$i + 1] == 'a') {
                $href = "";
                while (substr($html, $i++, 4) != "href");
                while ($html[$i] != "\'" && $html[$i] != "\"")
                    $i++;
                $i++;
                while ($html[$i] != "\'" && $html[$i] != "\"")
                    $href .= $html[$i++];
                $anchors[] = $href;
            }
        }
        return $anchors;
    }

    private function sameHost($url) {
        //return preg_match("/".addslashes($this->host)."/", $url);
        return (strpos($url, $this->host) === 0);
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
        foreach ($pieces as $piece) {
            if ($piece == RELEVANT_SECTION) {
                $this->relevant = true;
                return;
            }
        }
    }

    public function getPopularWords($key) {
        return $this->popular_words[$key];
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

    public function setVisited() {
        $this->visited = true;
    }

    public function isRelevant() {
        return $this->relevant;
    }
    
    public function setRelevant($tmp) {
        $this->relevant = $tmp;
    }

    public function extractPopularWords() {
        $this->deleteTagWithContent("script");
        $this->deleteTagWithContent("noscript");
        $this->deleteTagWithContent("head");
        $text = $this->plainText;
        $text = strip_tags($text);
        $text = preg_replace('/\s\s+\t\t+/', ' ', $text);
        file_put_contents("data/plain_html.html", $text);

        $all_words = array();

        preg_match_all("/[a-z]{4,}/", $text, $all_words);
        if (count($all_words) > 0) {
            $all_words = $all_words[0];
        }

        $words = array();
        $added_words = array();
        foreach ($all_words as $word_out) {
            $count = 0;
            if (!in_array($word_out, $added_words)) {
                foreach ($all_words as $word_in)
                    if ($word_out == $word_in)
                        $count++;
                $words[$word_out] = $count; //new Word($word_out, $count);

                $added_words[] = $word_out;
            }
        }

        arsort($words);
        /* foreach($words as $key=>$value){
          print_r("$key : $value");
          print_r("\n");
          }
          exit; */

        $this->popular_words = $words;
        return $this->popular_words;
    }
    
    //custom sorting function for associative arrays
    function sort($a, $b) {
        if ($a->getOccurrence() == $b->getOccurrence()) {
            return 0;
        }
        return ($a->getOccurrence() > $b->getOccurrence()) ? -1 : 1;
    }

    private function deleteTagWithContent($tagname) {
        $text = $this->plainText;

        $prefix = "<";
        $suffix = ">";

        $replacement = "";

        $open_tag = $prefix . $tagname;
        $close_tag = $prefix . '/' . $tagname . $suffix;
        $offset = strlen($close_tag);
        $open_tag_count = substr_count($text, $open_tag);
        $container = true; // Using this we can remove not-container tags easily NYI

        for ($i = 0; $i < $open_tag_count; $i++) {
            $from = strpos($text, $open_tag);
            $j = $from;
            while ($text[++$j] != $suffix);
            $to = ($text[$j - 1] == '/') ? $j + 1 : strpos($text, $close_tag) + $offset;
            $length = $to - $from;
            $text = substr_replace($text, $replacement, $from, $length);
        }
        //file_put_contents("123.html", $text); exit();
        $this->plainText = $text;
    }

}

?>
