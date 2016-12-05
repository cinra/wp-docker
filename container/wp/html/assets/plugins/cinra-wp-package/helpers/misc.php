<?php

/* ----------------------------------------------------------

  ￼CINRA Wordpress Package > Helpers > Misc

---------------------------------------------------------- */

function script_cleanup ($src) {return preg_replace( "/(\?|\&|\&(amp|#038);)ver=.*$/i", "", $src );}