<?php
/**
 * Plugin Name: Meta Composer
 * Version: 1.0
 * Description: render meta data on wp header.
 * Author: HAMADA, Satoshi (CINRA Inc,.)
 * Author URI: https://www.cinra.co.jp
 * Plugin URI: https://www.cinra.co.jp
 * Text Domain: meta-composer
 * @package CINRA Wordpress Package
 */

if (!defined( 'META_COMPOSER_AUTO_RENDER' )) define( 'META_COMPOSER_AUTO_RENDER', true );

class MetaComposer
{

  private $vals = [];

  public function __construct()
  {
    apply_filters( 'meta_composer_init', $this );
  }

  /**
   * set 値をセットする
   * @param array|string $vals 文字列の場合、MetaのNameの値となる
   * @param string $content      設定値
   * @param string $key_attr     MetaのNameにあたる部分。デフォルトは「name」
   * @param string $content_attr MetaのContentにあたる部分。デフォルトは「content」
   * @param string $tag          タグ。デフォルトは「meta」
   */
  public function set( $vals, $content = null, $key_attr = 'name', $content_attr = 'content', $tagname = 'meta' )
  {
    if ( !is_array($vals) ) $vals = array(
      array(
        'key'             => $vals,
        'content'         => $content,
        'key_attr'        => $key_attr,
        'content_attr'    => $content_attr,
        'tagname'         => $tagname,
      ),
    );

    foreach ($vals as $val)
    {
      $this->vals[$val['key']] = array_merge(array(
        'key'             => null,
        'content'         => null,
        'key_attr'        => 'name',
        'content_attr'    => 'content',
        'tagname'         => 'meta',
      ), $val);
    }
  }

  /**
   * get 値を取得する
   * @param  string $key     キー。nullの場合は、配列全部返す
   * @param  string $default 値がなかった時のデフォルト値
   * @return array|string
   */
  public function get( $key = null, $default = null )
  {
    if (!$key) return $this->vals;
    return isset( $this->vals[$key] ) ?: $default;
  }

  public function compose()
  {
    $html = "";
    if ($this->get())
    {
      foreach($this->get() as $val)
      {
        $html .= "<{$val['tagname']} {$val['key_attr']}=\"{$val['key']}\" {$val['content_attr']}=\"{$val['content']}\">\n";
      }
    }
    return apply_filters( 'meta_composer_compose', $html);
  }

  public function render()
  {
    echo $this->compose();
  }

}

function init_meta_composer()
{
  global $wp_query;
  $wp_query->meta_composer = new MetaComposer;
}
add_action( 'wp', 'init_meta_composer' );

function set_meta_data($key, $content = null, $key_attr = 'name', $content_attr = 'content', $tagname = 'meta')
{
  global $wp_query;
  $wp_query->meta_composer->set( $key, $content, $key_attr, $content_attr, $tagname );
}

function render_meta_data()
{
  global $wp_query;
  $wp_query->meta_composer->render();
}
if (META_COMPOSER_AUTO_RENDER) add_action( 'wp_head', 'render_meta_data' );