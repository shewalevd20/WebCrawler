<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function print_help() {
    print_r("\n-------------------------------------------------------------------------");
    print_r("\n| WebCrawler Usage:                                                     |");
    print_r("\n-------------------------------------------------------------------------\n");
    print_r("|--politeness=SECONDS - specifies politeness in seconds (Optional)      |\n");
    print_r("|--maxpages=INTEGER   - maximum number of pages to crawl (Optional)     |\n");
    print_r("|--seed_url=URL       - starting point for crawler (Required)           |\n");
    print_r("-------------------------------------------------------------------------");
    print_r("\n| Example:                                                              |\n");
    print_r("-------------------------------------------------------------------------\n");
    print_r("|php crawl.php --politeness=30 --maxpages=42 --seed_url=http://linux.org|\n");
    print_r("-------------------------------------------------------------------------\n\n");
}

?>
