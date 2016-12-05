<?php

// 1. customize ACF path
add_filter('acf/settings/path', 'cwp_acf_settings_path');

function cwp_acf_settings_path( $path )
{

  // update path
  $path =  CWP_PLUGIN_PATH . '/libs/acf/advanced-custom-fields-pro/' ;

  // return
  return $path;

}


// 2. customize ACF dir
// add_filter('acf/settings/dir', 'my_acf_settings_dir');

// function my_acf_settings_dir( $dir ) {

//     // update path
//     $dir = CWP_PLUGIN_PATH . '/libs/acf/advanced-custom-fields-pro/assets' ;

//     // return
//     return $dir;

// }


// 3. Hide ACF field group menu item
// add_filter('acf/settings/show_admin', '__return_false');


// 4. Include ACF
include_once( CWP_PLUGIN_PATH . '/libs/acf/advanced-custom-fields-pro/acf.php' );

/* ----------------------------------------------------------

  ￼ACFのプレビューバグ対応

---------------------------------------------------------- */

add_filter('_wp_post_revision_fields', 'add_field_debug_preview');
function add_field_debug_preview ($fields)
{
  $fields["debug_preview"] = "debug_preview";
  return $fields;
}

add_action( 'edit_form_after_title', 'add_input_debug_preview' );
function add_input_debug_preview()
{
  echo '<input type="hidden" name="debug_preview" value="debug_preview">';
}