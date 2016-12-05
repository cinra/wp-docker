<?php


function the_postman_shortcode($atts, $content=null, $tag)
{
  $value = get_postman($tag);
  if(is_array($value)) $value = implode(', ', $value);

  return $value;
}

global $postman;

$fields = $postman->get_vals();

foreach ($fields as $key => $field)
{
  add_shortcode($key, 'the_postman_shortcode');
}
