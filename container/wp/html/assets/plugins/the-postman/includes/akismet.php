<?php

class Postman_Akismet
{

  /**
   * do_filter function
   * @param  array $args to, subject, body
   * @return boolean
   */
  function do_filter( &$args )
  {

    global $akismet_api_host, $akismet_api_port;

    $mail = array(
      'comment_author' => '',
      'comment_author_email' => $args['to'],
      'comment_author_url' => '',
      'comment_content' => $args['body'],
      'blog' => get_option('home'),
      'blog_locale' => get_locale(),
      'blog_charset' => get_option('blog_charset'),
      'user_ip' => $_SERVER['REMOTE_ADDR'],
      'user_agent' => $_SERVER['HTTP_USER_AGENT'],
      'referrer' => $_SERVER['HTTP_REFERRER'],
      'comment_type' => 'contact-form',
      'permalink' => get_permalink(),
    );

    foreach ( $_SERVER as $k => $v )
    {
      if ( ! in_array( $k, array( 'HTTP_COOKIE', 'HTTP_COOKIE2', 'PHP_AUTH_PW' ) ) ) $mail[$k] = $v;
    }

    $query = $this->build_query($mail);

    if ( is_callable( array( 'Akismet', 'http_post' ) ) )
    {
      $response = Akismet::http_post( $query, 'comment-check' );
    }
    else
    {
      $response = akismet_http_post( $query, $akismet_api_host,
        '/1.1/comment-check', $akismet_api_port );
    }

    if ($response[1] == 'true') $args['is_spam'] = true;

  }

  /**
   * is_available function
   * @return boolean
   */
  function is_available()
  {

    // 3.0+
    if ( is_callable( array( 'Akismet', 'get_api_key' ) ) )
    {
      return (bool) Akismet::get_api_key();
    }

    // 2.9.1-
    if ( function_exists( 'akismet_get_key' ) ) {
      return (bool) akismet_get_key();
    }

    return false;
  }

  /**
   * build_query function.
   * @param  array $args
   * @param  string $key
   * @return string
   */
  function build_query( $args, $key = null )
  {

    $arr = array();

    foreach ( (array)$args as $k => $v )
    {

      if ( $key ) $k = $key . '[' . $k . ']';

      $k = urlencode( $k );

      if ( $v === null ) continue;
      if ( $v === false ) $v = '0';

      if ( is_array( $v ) || is_object( $v ) )
      {
        $arr[] = $this->build_query( $v, $k );
      }
      else
      {
        $arr[] = $k . '=' . urlencode( $v );
      }

    }

    return implode( '&', $arr );
  }

}

$akismet = new Postman_Akismet;

if ($akismet->is_available())
{
  add_filter('postman_set_mail', array( &$akismet, 'do_filter' ));
}

