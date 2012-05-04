<?php

/*
 * RMIT University | School of Computer Science & IT
 * COSC 1165 / 1167 — Intelligent Web Systems 
 * Assignment 2 | Web Crawler and Mining
 * 
 * @author Karim Abulainine  s3314713
 * @author Daniel Stankevich s3336691
 */

$file_content = file_get_contents("data/links.csv");
$lines = explode("\n", $file_content);
$pages = array();
foreach($lines as $line){
    $pages[] = explode(",", $line);
}
    
?>

<!--
 * RMIT University | School of Computer Science & IT
 * COSC 1165 / 1167 — Intelligent Web Systems 
 * Assignment 2 | Web Crawler and Mining
 * 
 * @author Karim Abulainine  s3314713
 * @author Daniel Stankevich s3336691
 -->

<html>
    <head>
        <title>Web Crawler - Articles</title>
    </head>
    <body>
        <form method="POST" action="getFeedback.php">
            <table>
                <tr>
                    <th>Article URL</th>
                    <th>Is this a mobile article</th>
                </tr>
                <?php
                $counter = 0;
                foreach ($pages as $page) {
                    $url = $page[0];
                    $isMobile = $page[count($page) - 1];
                    $occurrences = "";
                    for($i=1; $i< count($page) - 1; $i++){
                        if($i==1){
                            $occurrences .= "{$page[$i]}";
                        }else{
                            $occurrences .= ",{$page[$i]}";
                        }
                    }
                    
                ?>
                    <tr>
                        <td>
                            <a href="<?php echo $url ?>"><?php echo $url ?></a>
                            <input type="hidden" name="url[]" value="<?php echo $url ?>"/>
                            <input type="hidden" name="occurrences[]" value="<?php echo $occurrences ?>"/>
                        </td>
                        <td>
                            <input type="radio" name="isMobile[<?php echo $counter; ?>]" value="1" <?php if($isMobile){echo 'checked';}?>/>Yes
                            <input type="radio" name="isMobile[<?php echo $counter; ?>]" value="0" <?php if(!$isMobile){echo 'checked';}?>/>No
                        </td>
                    </tr>
                    <?php
                    $counter++;
                }
                ?>
            </table>
            <input type="submit" value="Submit" name="Submit"/>
        </form>
    </body>
</html>
