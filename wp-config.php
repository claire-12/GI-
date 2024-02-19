<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'webshop' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '-FCQS9^; Lou@-=GYluljk7!mPQ/M7i$de(%P_{u1_!#+Sem#Acv<)QA(6z=Pb6k');
define('SECURE_AUTH_KEY',  ' )_*Fbl6. aAAepLA]d!KEHa--@=O<p*jbiBd.p->JMvN~2-ISO^>`U+y!Z4qlgZ');
define('LOGGED_IN_KEY',    '$+uT!8f?<>g_Doay6*iy|Z9`xKZy}ghzxsPS<,h&Q#%HsM.K(4!QJ-]+CJ_QOEk^');
define('NONCE_KEY',        'm714O.sdi BDC+G<AFonEW^Hy;Xv*B9#fE%fsJ5-WV2XH10s&+TfXtMdB8s_]40x');
define('AUTH_SALT',        '[K>QAQw=N25Zajq$.#;$v;;$|g/o<>~{0Hd1wAX 7 e+;)3HBA27}4#dk@ d$VRA');
define('SECURE_AUTH_SALT', 'SF4+bw{yQjhwJu$E4riJ*wT.?h|vaaw*{|Y48*$p6t<Z }+~*Rl(qHC]+/;`ZeF$');
define('LOGGED_IN_SALT',   'Tk%]H~ltcP7%79 ;&ik2qsX ;4d06Je+6__vpd3I]$>dQ1za{>qPa7[5o|,Bj{`j');
define('NONCE_SALT',       'Tft+H:wNG?&+jqdPg2aUdYIzK/0?cQg{xEO.^R/kaL9MikQ]~-Kc},Dk>N9)rY8i');

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'ws_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', true );
define('WP_DEBUG_DISPLAY', true);

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
