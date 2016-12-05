<?php
/*
Plugin Name: the Postman
Plugin URI: http://www.cinra.co.jp
Description: I wanna send you a mail! This plugin is inspired by the movie written by Kevin Costner in 1997.
Author: HAMADA, Satoshi
Author URI: http://www.cinra.co.jp
Text Domain: the-postman
Domain Path: /lang/
Version: 0.1.1
*/

/*  Copyright 2014 HAMADA, Satoshi (email : tkcs@pelepop.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('POSTMAN_BASE_PATH', dirname(__FILE__));

if (!is_admin()) add_action('template_redirect', 'action_sound_postman');

function action_sound_postman()
{

  include_once(POSTMAN_BASE_PATH . '/postman.class.php');

  global $postman;
  $postman = new Postman();

  do_action('postman_init');

  include_once(POSTMAN_BASE_PATH . '/includes/functions.php');
  include_once(POSTMAN_BASE_PATH . '/includes/shortcode.php');
  include_once(POSTMAN_BASE_PATH . '/includes/akismet.php');

  load_plugin_textdomain( 'the-postman', false, dirname(plugin_basename(__FILE__)).'/lang' );

  if ($_POST && isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'the-postman'))
  {

    global $postman;

    $results = $postman->validate();

    if ($results['status'] === 'success')
    {
      $result = $postman->mail();

      if (!$result) die('Mail Error');

      if ($postman->get('_finished'))
      {
        if ($postman->get('_finish_page')) header('location:'.$postman->get('_finish_page'));
      }

    }

  }

}

if (is_admin())
{

  include_once(POSTMAN_BASE_PATH . '/postman.class.php');

  global $postman;
  $postman = new Postman();

  load_plugin_textdomain( 'the-postman', false, dirname(plugin_basename(__FILE__)).'/lang' );

  include_once(POSTMAN_BASE_PATH . '/admin/menus.php');

}

