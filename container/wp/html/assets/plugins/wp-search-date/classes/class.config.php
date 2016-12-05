<?php

class WP_Search_Date_Config
{

  public static $options = array(
    'post_type'   => array(),
    // ex: 'post', 'page', 'attachment', 'revision', 'nav_menu_item', '{custompost key}'

    'category'    => array(),
    // category slug

    'keys'         => array('start' => '', 'end' => '',),
  );

  public function __construct()
  {
    do_action('search_date_config_init');
  }

  public static function set($key, $value = null)
  {
    if($key)
    {
      self::$options[$key] = $value;
    }
    else
    {
      self::$options = $value;
    }
  }

  public static function get($key = null)
  {
    do_action('search_date_config_init');
    return ($key && isset(self::$options[$key])) ? self::$options[$key] : self::$options;
  }
}