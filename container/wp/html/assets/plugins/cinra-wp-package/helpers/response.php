<?php

/* ----------------------------------------------------------

  ￼CINRA Wordpress Package > Helpers > Response

---------------------------------------------------------- */

/**
 * do_404 function.
 * @access public
 */
function do_404()
{
  status_header( 404 );
  get_template_part('404');
  exit;
}