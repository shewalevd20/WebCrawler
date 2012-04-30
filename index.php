<?php
require_once 'class/WebPage.class.php';
require_once 'class/WebCrawler.class.php';

$pages = WebCrawler::getVisitedPages();
?>
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
                            <a href="<?php echo $page->getUrl() ?>"><?php echo $page->getUrl() ?></a>
                            <input type="hidden" name="url[]" value="<?php echo $page->getUrl() ?>"/>
                        </td>
                        <td>
                            <input type="radio" name="isMobile[<?php echo $counter; ?>]" value="1" <?php if($page->isMobileArticle()){echo 'checked';}?>/>Yes
                            <input type="radio" name="isMobile[<?php echo $counter; ?>]" value="0" <?php if(!$page->isMobileArticle()){echo 'checked';}?>/>No
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
