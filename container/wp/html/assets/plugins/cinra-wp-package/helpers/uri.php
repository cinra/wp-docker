<?php

/* ----------------------------------------------------------

  ￼CINRA Wordpress Package > Helpers > Uri

---------------------------------------------------------- */

/**
 * get_canonical_url function.
 *
 * @access public
 * @return void
 */
function get_canonical_url( $lang = null )
{
  if (!$lang) $lang = get_locale();
  $scheme   = isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http';
  $paths    = pathinfo( preg_replace( '/\?.*/', '', $_SERVER['REQUEST_URI']) );
  $dir      = isset($paths['dirname']) ? trim($paths['dirname'], '/') : null;
  $file     = isset($paths['filename']) ? trim($paths['filename'], '/') : null;
  $url      = $scheme.'://'. trim($_SERVER['HTTP_HOST'], '/').'/';
  if ($lang && $lang !== CWP_DEFAULT_LANG) $url .= $lang . '/';
  if ($dir) $url .= $dir . '/';
  if ($file) $url .= $file . '/';
  return $url;
}

/**
 * get_uri_segment
 * URI Pathを取得
 * @param  boolean $segment [description]
 * @return [type]           [description]
 */
function get_uri_segment ($segment = false)
{
  global $wp, $home;
  $request = str_replace(get_permalink($home->ID), '', get_canonical_url());
  $segments = explode('/', trim($request, '/'));
  if ($segment)
  {
    if ($segment > 0) $segment--;
    return (isset($segments[$segment])) ? $segments[$segment] : false;
  }
  return $uri;
}