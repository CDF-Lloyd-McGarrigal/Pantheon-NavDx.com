<?php


/*
 * Removing Dashboard Widgets
 */
function arteric_wp_base_remove_dashboard_widgets(){
    remove_meta_box('dashboard_activity', 'dashboard', 'normal');   // Activity
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal');   // Right Now
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal'); // Recent Comments
    remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');  // Incoming Links
    remove_meta_box('dashboard_plugins', 'dashboard', 'normal');   // Plugins
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');  // Quick Press
    remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');  // Recent Drafts
    remove_meta_box('dashboard_primary', 'dashboard', 'side');   // WordPress blog
    remove_meta_box('dashboard_secondary', 'dashboard', 'side');   // Other WordPress News
    remove_meta_box('rg_forms_dashboard', 'dashboard', 'normal');   // Gravity Forms Widget
    remove_meta_box( 'wpseo-dashboard-overview', 'dashboard', 'side' ); // Yoast SEO dashboard overview
}
add_action('wp_dashboard_setup', 'arteric_wp_base_remove_dashboard_widgets');