<?php

function get_postman($key = null)
{
  
  global $postman;
  
  if (!$key) return null;
  
  return $postman->get($key);
  
}

function the_postman($key = null)
{
  
  echo esc_html(get_postman($key));
  
}

function postman_is_error($key = null)
{
  
  global $postman;
  
  return $postman->is_error($key);
  
}

function postman_is_confirmed()
{
  
  if (get_postman('_status') == 'confirm' && !postman_is_error()) return true;

}

function postman_get_message($key, $is_array = true, $options = array())
{
  
  global $postman;
  
  $options = array_merge(array(
    'delimiter' => ', ',
    'wrapper-open' => '',
    'wrapper-close' => '',
    'item-open' => null,
    'item-close' => '',
  ), $options);
  
  $messages = $postman->get_message($key);
  
  if ($is_array) return $messages;
  
  $output = "";
  
  $output .= $options['wrapper-open'];
  
  if ($options['item-open'] && $options['item-close'])
  {
    foreach ($messages as $message)
    {
      $output .= $options['item-open'].$message.$options['item-close'];
    }
  }
  else
  {
    $output .= implode($options['delimiter'], $messages);
  }
  
  $output .= $options['wrapper-close'];
  
  return $output;
  
}

function postman_the_message($key, $is_array = false, $options = array())
{
  echo postman_get_message($key, $is_array, $options);
}