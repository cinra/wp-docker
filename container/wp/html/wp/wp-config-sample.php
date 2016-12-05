<?php
/**
 * WordPress の基本設定
 *
 * このファイルは、インストール時に wp-config.php 作成ウィザードが利用します。
 * ウィザードを介さずにこのファイルを "wp-config.php" という名前でコピーして
 * 直接編集して値を入力してもかまいません。
 *
 * このファイルは、以下の設定を含みます。
 *
 * * MySQL 設定
 * * 秘密鍵
 * * データベーステーブル接頭辞
 * * ABSPATH
 *
 * @link http://wpdocs.sourceforge.jp/wp-config.php_%E3%81%AE%E7%B7%A8%E9%9B%86
 *
 * @package WordPress
 */

// 注意:
// Windows の "メモ帳" でこのファイルを編集しないでください !
// 問題なく使えるテキストエディタ
// (http://wpdocs.sourceforge.jp/Codex:%E8%AB%87%E8%A9%B1%E5%AE%A4 参照)
// を使用し、必ず UTF-8 の BOM なし (UTF-8N) で保存してください。

// ** MySQL 設定 - この情報はホスティング先から入手してください。 ** //
/** WordPress のためのデータベース名 */
define('DB_NAME', 'cinra');

/** MySQL データベースのユーザー名 */
define('DB_USER', 'vagrant');

/** MySQL データベースのパスワード */
define('DB_PASSWORD', 'vagrant');

/** MySQL のホスト名 */
define('DB_HOST', 'localhost');

/** データベースのテーブルを作成する際のデータベースの文字セット */
define('DB_CHARSET', 'utf8');

/** データベースの照合順序 (ほとんどの場合変更する必要はありません) */
define('DB_COLLATE', '');

/**#@+
 * 認証用ユニークキー
 *
 * それぞれを異なるユニーク (一意) な文字列に変更してください。
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org の秘密鍵サービス} で自動生成することもできます。
 * 後でいつでも変更して、既存のすべての cookie を無効にできます。これにより、すべてのユーザーを強制的に再ログインさせることになります。
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'n0YrSg=#I-bAn<^pH?84iqV)M&eC-VVV1[!hd76.-sfKXt/Ue?<(Fr{R*UbQqlpq');
define('SECURE_AUTH_KEY',  'twZ?U.Bxa<8Zzcla]2tc;|mwQ{Ep0^+CeazR@#Jly<e%GO@Axju+]_Z<I+Ss-s2y');
define('LOGGED_IN_KEY',    '$bw$&[3OTM:o$==|2 L3Txe5)Q7e;d)tB:dYHFK.-*#0!)e1dEneWN7e,3TMC0AL');
define('NONCE_KEY',        '+[JdY*+rHq|kX |?ZqOF:g,bTK/a|CwJDY5eVgvBO~)~lu7:VOhAA?B1e(N+<S`C');
define('AUTH_SALT',        'Sx+ypJ~9por_A $YbnE]@?s):xF7t>fhLx,>7_~=4-(z[mUSRam]s3L*mtJ[zvj ');
define('SECURE_AUTH_SALT', 'Xy)Q)Zu7cP*PD!QF?xX-$Z) vNqAEfH47rKekP`A5)Fy+I7r+DyJ,;n:(&~KoJbI');
define('LOGGED_IN_SALT',   'yXT;+/+ k]xeq!$f>T3mqwtLhjd%+Gz.7i|-&mH8ZT8@% FwZz>.da<b`+XF]:DU');
define('NONCE_SALT',       '+ $n$YkW?ft%*RK_dtp pgh-1wnRY}Ux+L8-3 (jK_}e4,E_n6QQM)_#NR.]tt~Q');

/**#@-*/

/**
 * WordPress データベーステーブルの接頭辞
 *
 * それぞれにユニーク (一意) な接頭辞を与えることで一つのデータベースに複数の WordPress を
 * インストールすることができます。半角英数字と下線のみを使用してください。
 */
$table_prefix  = 'wp_';

/**
 * 開発者へ: WordPress デバッグモード
 *
 * この値を true にすると、開発中に注意 (notice) を表示します。
 * テーマおよびプラグインの開発者には、その開発環境においてこの WP_DEBUG を使用することを強く推奨します。
 *
 * その他のデバッグに利用できる定数については Codex をご覧ください。
 *
 * @link http://wpdocs.osdn.jp/WordPress%E3%81%A7%E3%81%AE%E3%83%87%E3%83%90%E3%83%83%E3%82%B0
 */
define('WP_DEBUG', false);

// wp-contentディレクトリの位置を指定
define('WP_CONTENT_DIR', $_SERVER['DOCUMENT_ROOT'] . '/assets');
define('WP_CONTENT_URL', 'http://'.$_SERVER["HTTP_HOST"].'/assets');

// CINRA Wordpress Package
define('CWP_PROJECT_NAME', 'cinra');

/* 編集が必要なのはここまでです ! WordPress でブログをお楽しみください。 */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
  define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
