<?php

/* ----------------------------------------------------------

  ￼CINRA Wordpress Package > I18n

---------------------------------------------------------- */

define( 'CWP_IS_I18N', true );
if ( !defined('CWP_DEFAULT_LANG') ) define( 'CWP_DEFAULT_LANG', 'ja' );

/**
 * i18n_set_locale
 * ロケールを設定する
 * @param  string $locale ロケール
 * @return string         ロケール
 */
function i18n_set_locale( $locale )
{
  if ( is_admin() ) return $locale;
  return defined('WPLANG') ? WPLANG : CWP_DEFAULT_LANG;
}
add_filter( 'locale', 'i18n_set_locale' );

function i18n_load_textdomain()
{
  $locale = defined('WPLANG') ? WPLANG : CWP_DEFAULT_LANG;
  load_textdomain( CWP_PROJECT_NAME, get_stylesheet_directory() . '/lang/' . $locale . '.mo' );
}
add_action('init', 'i18n_load_textdomain');

if( class_exists('acf') )
{
  function get_i18n_field( $key, $post_id = null )
  {
    $value = get_field( get_i18n_key($key), $post_id );

    if (!$value)
    {
      $alt = get_field('alternate_language', $post_id);
      if ($alt && $alt !== get_locale())
      {
        $value = get_field($alt.'_'.$key, $post_id);
      }
    }

    return $value;
  }

  function the_i18n_field( $key, $post_id = null )
  {
    echo get_i18n_field( $key, $post_id );
  }

  function get_i18n_sub_field( $key )
  {
    return get_sub_field( get_i18n_key($key) );
  }

  function get_i18n_term_field( $key, $term_id, $taxonomy)
  {
    $value = get_field( get_i18n_key($key), "{$taxonomy}_{$term_id}");

    if (!$value)
    {
      $term = get_term($term_id, $taxonomy);
      if ($term && isset($term->{$key})) $value = esc_html($term->{$key});
    }

    return $value;
  }

  function the_i18n_sub_field( $key )
  {
    echo get_i18n_sub_field( $key );
  }

  function get_i18n_key( $key )
  {
    if (!$key) return null;
    return get_locale() . '_' . $key;
  }

  function i18n_title_filter( $raw )
  {
    if (is_admin()) return $raw;

    $title = get_i18n_field('title');
    return !$title ? $raw : $title;
  }
  add_filter( 'the_title', 'i18n_title_filter' );

  function i18n_content_filter( $raw )
  {
    if (is_admin()) return $raw;

    $content = get_i18n_field('content');
    return !$content ? $raw : $content;
  }
  add_filter( 'the_content', 'i18n_content_filter' );

  // function i18n_tax_query( $query )
  // {
  //   if (is_admin()) return;

  //   $locale = get_locale();

  //   $tax_query = $query->get('tax_query');

  //   $tax_query[] = array(
  //     'taxonomy' => 'language',
  //     'field'    => 'slug',
  //     'terms'    => $locale
  //   );

  //   $query->set('tax_query', $tax_query);
  // }
  // add_action( 'pre_get_posts', 'i18n_tax_query' );

  // function i18n_post_filter($pieces, $query)
  // {
  //   if (is_admin()) return $pieces;

  //   global $wpdb;

  //   if (!$query->is_main_query() && $query->is_singular())
  //   {
  //     $q = $query->query_vars;
  //     $query->parse_tax_query($q);
  //     $clauses = $query->tax_query->get_sql($wpdb->posts, 'ID');
  //     $pieces['where'] .= $clauses['where'];
  //     $pieces['join'] .= $clauses['join'];
  //   }

  //   return $pieces;
  // }
  // add_filter( 'posts_clauses_request', 'i18n_post_filter', 10, 2 );
}

/**
 * i18n_home_url
 * 各言語別のhome_url()
 * @param  string $url  URL
 * @param  string $path PATH
 * @return string       変換後のURL
 */
function i18n_home_url( $url, $path )
{
  if (is_admin() || !$path) return $url;

  $locale = get_locale();
  if ($locale === 'ja') return $url;

  $path = '/' . trim($path, '/');

  return $path === '/' ? $url.$locale.'/' : str_replace($path, '/' . $locale.$path , $url) . '/';
}
add_action( 'home_url', 'i18n_home_url', 10, 2 );
