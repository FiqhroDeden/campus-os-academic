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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

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
define( 'AUTH_KEY',          'fo?DCeh**9Fc{mw`+pSb64oMg7AMBBaty/-HO4RXIp:zMFIb;,{uVoE>%]cDm1CF' );
define( 'SECURE_AUTH_KEY',   '1h:M|*4Q&ERH+uy0i?|W}.4K#</[|-4Lx-i-)inBwhef_(!d)s{%XQ{8w?KcI[R!' );
define( 'LOGGED_IN_KEY',     'K=r4cw8%]eZFc;( [LczNxC*/C$tgb7J^/TPc[OU/UB|Vne^+6@LRmwXM:j$zeIg' );
define( 'NONCE_KEY',         'Q.)#D:>oE&&d*aSS[]PXaVW}f|mqoUgw?i_X9l`%L-F~k,`+]Os|W+m]F{P3!|e%' );
define( 'AUTH_SALT',         'O0:$?mw21uH{DZn_*_SqYjlYfa0%lbwx0I3|p/4X057y!$%e-8;O/K;F, +@(?*?' );
define( 'SECURE_AUTH_SALT',  '7k)6%T>@]j);#bq8Kg=71V;*6J3,$q^Lygc6o,,>KDQ&-`P?o ,M+kHnfQk60a[?' );
define( 'LOGGED_IN_SALT',    'Kv`U2,O]*@nP[,#d0;j8v/d|Ln7]B)K)X*RuC$*9BF{I&XpLTj:@g4{tZ8@,W1IC' );
define( 'NONCE_SALT',        'TG{7>HD$&igLu0Eeb;[iW1=hrqdd|#YRR4gNaZ_C0)D7oWfA<QhRt47Qb/|vH4Q*' );
define( 'WP_CACHE_KEY_SALT', '+e)Lv;->@K_oD;JXqaF1PYa`Ys;HjM2R;i?m:}2@w9I*QOX7xZ6bz?cn}29VWZXx' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
