<?php

/* ----------------------------------------------------------

  ￼Posts

---------------------------------------------------------- */

function manage_posts_columns($columns)
{
  // unset($columns['author']);
  unset($columns['tags']);
  unset($columns['comments']);

  $columns['image'] = 'アイキャッチ画像';

  return $columns;
}
function manage_pages_columns($columns)
{
  // unset($columns['author']);
  unset($columns['comments']);

  return $columns;
}
function add_posts_column($column_name, $post_id)
{
  if( $column_name === 'image' )
  {
    $thumbnail = get_the_post_thumbnail($post_id, array(50,50), 'thumbnail');
    echo $thumbnail ?: 'なし';
  }
}
add_filter( 'manage_posts_columns', 'manage_posts_columns' );
add_filter( 'manage_pages_columns', 'manage_pages_columns' );
add_action( 'manage_posts_custom_column', 'add_posts_column', 10, 2 );

/* ----------------------------------------------------------

  ￼Metaboxes

---------------------------------------------------------- */

function remove_default_page_screen_metaboxes()
{

  remove_meta_box('postexcerpt', 'page', 'normal'); // 抜粋
  remove_meta_box('commentstatusdiv', 'page', 'normal'); // コメント
  remove_meta_box('trackbacksdiv', 'page', 'normal'); // トラバ

  remove_meta_box('postexcerpt', 'post', 'normal'); // 抜粋
  remove_meta_box('commentsdiv', 'post', 'normal'); // コメント
  remove_meta_box('commentstatusdiv', 'post', 'normal'); // コメント
  remove_meta_box('trackbacksdiv', 'post', 'normal'); // トラバ
  remove_meta_box('tagsdiv-post_tag', 'post', 'side'); // 投稿のタグ
  remove_meta_box('postcustom', 'post', 'normal'); // カスタムフィールド

  remove_meta_box('dashboard_primary', 'dashboard', 'normal'); // WPニュース

}
add_action('admin_menu', 'remove_default_page_screen_metaboxes');

/* ----------------------------------------------------------

  ￼Menus

---------------------------------------------------------- */

function remove_menu()
{

  remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=post_tag');// 投稿の「タグ」を非表示
  remove_submenu_page('index.php', 'update-core.php'); // 更新
  remove_submenu_page('themes.php', 'theme-editor.php'); // カスタマイズ
  remove_menu_page('edit-comments.php'); // コメント

  global $submenu;
  unset($submenu['themes.php'][6]); // Customize

}
add_action('admin_menu', 'remove_menu');