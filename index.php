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
                foreach ($pages as $page) {
                    ?>
                    <tr>
                        <td>
                            <a href="<?php echo $page->getUrl() ?>"><?php echo $page->getUrl() ?></a>
                        </td>
                        <td>
                            <input type="radio" name="isMobile" value="1" <?php if($page->isMobileArticle()){echo 'checked';}?>/>Yes
                            <input type="radio" name="isMobile" value="0" <?php if(!$page->isMobileArticle()){echo 'checked';}?>/>No
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <input type="submit" value="Submit" name="Submit"/>
        </form>
    </body>
</html>
