<?php

class WP_Search_Date_Admin
{

  public function __construct() {
    add_action('save_post', array($this, 'save_date_meta'));
  }

  public function save_date_meta($post_id)
  {
    global $wpdb;

    $table_name = SEARCH_DATE_TABLE;

    $options = WP_Search_Date_Config::get();
    if(!in_array(get_post_type($post_id), $options['post_type'])) return;
    if($options['category'] && !in_category($options['category'], $post_id)) return;

    $post = get_post($post_id);
    if (empty($post)) return;

    $keys = $options['keys'];
    $additional_data = array();

    foreach ($keys as $k => $key)
    {
      $value = '';

      if(strpos($key, 'acf_') === 0)
      {
        $value = get_field(str_replace('acf_', '', $key));
      }
      else
      {
        $value = get_post_meta($post_id, $key);
      }

      if(!is_array($value))
      {
        $additional_data[$k] = strtotime($value);
      }

    }

    $savedata = array(
      'post_id' => $post_id,
    );
    $savedata = array_merge($savedata, $additional_data);
    $pid = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM {$table_name} WHERE post_id = %d", $post_id));

    if (!empty($pid))
    {
      $wpdb->update("{$table_name}", $savedata, array('post_id' => $post_id));
    }
    else
    {
      $res = $wpdb->insert("{$table_name}", $savedata);
    }
  }
}