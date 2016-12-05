<?php

class WP_Search_Index_Admin
{

  public function __construct() {
    add_action('save_post', array($this, 'save_index_meta'));
  }

  public function save_index_meta($post_id)
  {
    global $wpdb;

    $table_name = SEARCH_INDEX_TABLE;

    $options = WP_Search_Index_Config::get();
    if(!in_array(get_post_type($post_id), $options['post_type'])) return;
    // if($options['category'] && !in_category($options['category'], $post_id)) return;

    $post = get_post($post_id);
    if (empty($post)) return;

    $keys = $options['keys'];
    $additional_data = array();

    foreach ($keys as $key)
    {
      $value = '';
      $get_values = array();

      if(strpos($key, 'acf_') === 0)
      {
        $get_values = get_field(str_replace('acf_', '', $key), $post_id);

        if(is_array($get_values))
        {
          foreach($get_values as $v)
          {
            $value .= '*[' .$v .']* ';
          }
        }
        else
        {
          $value = $get_values;
        }
      }
      elseif(strpos($key, 'taxonomy_') === 0)
      {
        if($get_values = get_the_terms($post_id, str_replace('taxonomy_', '', $key)))
        {
          foreach($get_values as $taxonomy) $value .= '*[' .$taxonomy->slug .']* ';
        }
      }
      elseif(strpos($key, 'category') === 0)
      {
        if($get_values = get_the_category($post_id))
        {
          foreach($get_values as $category) $value .= '*[' .$category->slug .']* ';
        }
      }
      else
      {
        $get_values = get_post_meta($post_id, $key);

        if(is_array($get_values))
        {
          foreach($get_values as $v)
          {
            $value .= '*[' .$v .']* ';
          }
        }
        else
        {
          $value = $get_values;
        }
      }

      $additional_data[$key] = $value;

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