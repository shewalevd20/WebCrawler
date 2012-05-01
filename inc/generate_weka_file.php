<?php

/*
 * RMIT University | School of Computer Science & IT
 * COSC 1165 / 1167 — Intelligent Web Systems 
 * Assignment 2 | Web Crawler and Mining
 * 
 * @author Karim Abulainine  s3314713
 * @author Daniel Stankevich s3336691
 */

function generateWekaFile() {
    $classes = array(mobile => "Mobile", 
                     not_mobile => "Not_Mobile");
    
    $filename = "articles.arrf";
    fopen($filename, 'w');
    
    $weka_file = "";
    $weka_file .= "\n@RELATION ARTICLES";
    $weka_file .= "\n@ATTRIBUTE mobile {0,1}";
    $weka_file .= "\n@DATA";
    
    file_put_contents("articles.arrf", $weka_file);
    
    print_r("Weka file generated ('" . $filename . "')\n");
}

?>
