<?php
/**
 * RMIT
 * @author Daniel Stankevich
 * @author Karim Ainine
 */
function generateWekaFile() {
    $classes = array(mobile => "Mobile", 
                     not_mobile => "Not_Mobile");
    fopen("articles.arrf", 'w');
    $weka_file = "";
    $weka_file .= "\n@RELATION ARTICLES";
    $weka_file .= "\n@ATTRIBUTE mobile {0,1}";
    $weka_file .= "\n@DATA";
    file_put_contents("articles.arrf", $weka_file);
    print_r("\nWeka file generated\n");
}

?>
