<?php

/* ----------------------------------------------------------

  ￼CINRA Wordpress Package > Helpers > Condition

---------------------------------------------------------- */

/**
 * is_bot
 * Search Botの判定
 * @return boolean [description]
 */
function is_bot()
{
  $bots = array (
    'Googlebot',
    'Yahoo! Slurp',
    'Mediapartners-Google',
    'msnbot',
    'bingbot',
    'MJ12bot',
    'Ezooms',
    'pirst; MSIE 8.0;',
    'Google Web Preview',
    'ia_archiver',
    'Sogou web spider',
    'Googlebot-Mobile',
    'AhrefsBot',
    'YandexBot',
    'Purebot',
    'Baiduspider',
    'UnwindFetchor',
    'TweetmemeBot',
    'MetaURI',
    'PaperLiBot',
    'Showyoubot',
    'JS-Kit',
    'PostRank',
    'Crowsnest',
    'PycURL',
    'bitlybot',
    'Hatena',
    'facebookexternalhit',
  );
  foreach ($bots as $bot)
  {
    if (stripos($_SERVER['HTTP_USER_AGENT'], $bot) !== false) return $bot;
  }
  return false;
}