<?php

/*
 * RMIT University | School of Computer Science & IT
 * COSC 1165 / 1167 — Intelligent Web Systems 
 * Assignment 2 | Web Crawler and Mining
 * 
 * @author Karim Abulainine  s3314713
 * @author Daniel Stankevich s3336691
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
