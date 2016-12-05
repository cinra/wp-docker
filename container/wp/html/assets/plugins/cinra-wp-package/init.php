<?php

/* ----------------------------------------------------------

  ￼Init

---------------------------------------------------------- */

remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

remove_action( 'load-plugins.php', 'wp_update_plugins' );
remove_action( 'load-update.php', 'wp_update_plugins' );
remove_action( 'load-update-core.php', 'wp_update_plugins' );
remove_action( 'admin_init', '_maybe_update_core');
remove_action( 'admin_init', '_maybe_update_plugins' );
remove_action( 'wp_update_plugins', 'wp_update_plugins' );
remove_action( 'wp_version_check', 'wp_version_check' );

add_filter( 'pre_transient_update_plugins', function() { return null; } );
add_filter( 'pre_site_transient_update_core', function() { return null; } );
add_filter( 'pre_site_transient_update_plugins', function() { return null; } );

add_theme_support('post-thumbnails');

/* ----------------------------------------------------------

  JS

---------------------------------------------------------- */

function manage_js()
{
  if (!is_admin())
  {
    wp_deregister_script('jquery');
    // wp_enqueue_script('script', get_bloginfo('template_url').'/js/build.min.js', null, null, true);
    add_filter('script_loader_src', 'script_cleanup');
  }
}
add_action('wp_print_scripts', 'manage_js');

/* ----------------------------------------------------------

  CSS

---------------------------------------------------------- */

function manage_css()
{
  if (!is_admin())
  {
    // wp_enqueue_style('style', get_bloginfo('template_url').'/css/style.min.css');
    // wp_enqueue_style('print', get_bloginfo('template_url').'/css/print.min.css', array('style'), null, 'print');
    add_filter('style_loader_src', 'script_cleanup');
  }
}
add_action('wp_print_styles', 'manage_css');

/* ----------------------------------------------------------

  ￼Admin Bar

---------------------------------------------------------- */

function mytheme_remove_item( $wp_admin_bar )
{
  $wp_admin_bar->remove_node('customize');
  $wp_admin_bar->remove_node('appearance');
  $wp_admin_bar->remove_node('comments');
}
add_action( 'admin_bar_menu', 'mytheme_remove_item', 1000 );