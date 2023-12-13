<?php
/**
 * This file exists to include other metabox fields
 */

$cur_dir = dirname( __FILE__ );

// Include Page options
include_once $cur_dir . '/page_options/all-pages.php';

// Include the Site Options Page
include_once $cur_dir . '/site_options/site-options.php';

// Individual Site Options Tabs
include_once $cur_dir . '/site_options/header-options.php';
include_once $cur_dir . '/site_options/footer-options.php';
include_once $cur_dir . '/site_options/404-options.php';
include_once $cur_dir . '/site_options/social-options.php';
include_once $cur_dir . '/site_options/registration-form-options.php';