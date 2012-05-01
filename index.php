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
        <form method="POST" action="">
            <table>
                <tr>
                    <th>Article URL</th>
                    <th>Is this a mobile article</th>
                </tr>
                <?php
                $counter = 0;
                foreach ($pages as $page) {
                ?>
                    <tr>
                        <td>
                            <a href="<?php echo $page[0] ?>"><?php echo $page[0] ?></a>
                            <input type="hidden" name="url[]" value="<?php echo $page[0] ?>"/>
                        </td>
                        <td>
                            <input type="radio" name="isMobile[<?php echo $counter; ?>]" value="1" <?php if($page[1]){echo 'checked';}?>/>Yes
                            <input type="radio" name="isMobile[<?php echo $counter; ?>]" value="0" <?php if(!$page[1]){echo 'checked';}?>/>No
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
