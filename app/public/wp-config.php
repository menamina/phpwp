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
define( 'AUTH_KEY',          'ijHA?~)SOvH=s8Y~mq^Y-1q3tc[;|_zqgTTt>Xi|p{R^EJ_]6OZv]=w0t}}I/Yml' );
define( 'SECURE_AUTH_KEY',   'aaLrq0Xsd`cv$^Te|>u>x:Z)XJRAzz4@o:25_7|R^_<_a$F?%+:hvNhxr3U;D-L%' );
define( 'LOGGED_IN_KEY',     ')<nqj6-S^CGEZLDl!vL-ewIg.EaV`Mdt`En}Har^ua~|,ChmJmR&y$Pn|hlUkIjB' );
define( 'NONCE_KEY',         'Cwi1M1uMSI6cC68WYP>=1p`/el1<0`*Tg`;0PVOhzDc2qAS@C)fx _a90( uv@/!' );
define( 'AUTH_SALT',         '+=JIZo^&kz@oB&<=aidxPoTNZ(ree{*iq_0/s?|3[pC_qW!V[R`Na=Ywyr,]3]7>' );
define( 'SECURE_AUTH_SALT',  '(yG0?CzUr_;j$,~mj*.4:t@J+qC?s[doM*J5+tg~5*0Otl@su5&/Q4y0vrYquYaE' );
define( 'LOGGED_IN_SALT',    '#HAqu/Nh7Q>Y_JYfO,qc+VNuAti&ZEBz;x vl wUjeSpcl*DttjhIeoc_&@Yq&U;' );
define( 'NONCE_SALT',        'jC:TA}*g3/-6W:Cf]jqY-eOZXHI) k};16kS0BoJDy/E3#1R_qx.oDv0#|*#]bda' );
define( 'WP_CACHE_KEY_SALT', 'De~zP@3-[Y@}+n~s:9Q2P){C:;vc=Dt CM*4^J~/l_Y0:{FR0Vqop@*VJFn&3@<E' );


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
	define( 'WP_DEBUG', true );
	define( 'WP_DEBUG_LOG', true );
	define( 'WP_DEBUG_DISPLAY', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );

/* Custom API Keys */
define( 'AVIATIONSTACK_API_KEY', '7db0d516b096fa10389900afc2e4e375' );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
