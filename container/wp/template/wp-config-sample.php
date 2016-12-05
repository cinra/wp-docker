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
define('DB_NAME', $_ENV['WP_DB_NAME']);

/** MySQL データベースのユーザー名 */
define('DB_USER', $_ENV['WP_DB_USER']);

/** MySQL データベースのパスワード */
define('DB_PASSWORD', $_ENV['WP_DB_PASSWORD']);

/** MySQL のホスト名 */
define('DB_HOST', $_ENV['WP_DB_HOST']);

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
define('AUTH_KEY',         '+,&I1X19fSv6[c3|vpXpdF|LWaeP{z+}K>N-HZ>MSrbtu.lTP X.vl%2)^q>)Rl2');
define('SECURE_AUTH_KEY',  '9xc|VNky+7VqY}G%soVsV :Tbvf2-1(Pl2mv-gA=/C!VW}NOrVkgAyD:%Df{]H]y');
define('LOGGED_IN_KEY',    'I:*UJm<H=D/6>ixpmr]=YmJ-Z$$dRxx +bl|mdAFfGC6C-9|M+2>c/s9JRsm#{~)');
define('NONCE_KEY',        'AKrFyouPN?@ nsG0^|[DvPM+wYM%,S4nv]hip+>n2E_+.PuV{b76pm0-/z6cYCtL');
define('AUTH_SALT',        '[MrXXS^R`_Qrn8G`.N67*PXF2h-=[,XD|;flbTPf^H*UqpD)m}pWzk~BIhyq)zkB');
define('SECURE_AUTH_SALT', '~w48$B|c20$px*3^bppZ+9K~|g5)wj&wP=4jXPK0;$GOAN|{U!e3q9&z qmQj0.q');
define('LOGGED_IN_SALT',   'GKzlJfZD,$T.A(XxGr*^^ajp]wJg)EgYY.3j4_O|<yY1:Z/bLv8|Q6XEfE=(#){>');
define('NONCE_SALT',       'Ioyy5F|wX]-[T-p7o n!^PKQUrR=`6A4B|r1[Nd!A+8wP$%;rlu2YI{>`U|^Drc.');

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
define('CWP_PROJECT_NAME', $_ENV['WP_DB_HOST']);

/* 編集が必要なのはここまでです ! WordPress でブログをお楽しみください。 */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
  define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
