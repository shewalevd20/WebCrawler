<?php
$urls = $_POST['url'];
$isMobile = $_POST['isMobile'];
$occurrences = $_POST['occurrences'];

$articles = file_get_contents("data/articles.arff");

for($i=0; $i<count($urls); $i++){
    $articles .= "\n".$occurrences[$i].",".($isMobile[$i]?"Mobile":"Not-Mobile");
}

file_put_contents("data/articles.arff", $articles);

echo "<h2> Thank you for your feedback! </h2>";
?>
