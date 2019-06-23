<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'testproj001' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'P0imCS0m}BVjfW|Pk#b $bNyD!tDNm>yMYtb[V/51G^DbG-08Ej|S^6xj:d!oYH|' );
define( 'SECURE_AUTH_KEY',  '|&*E-n|5fT1r!x=gOB2IZ*_:jwmAeS*=lx/+_7A!/AgAp^z0=d[hFDqG{N2;7cz:' );
define( 'LOGGED_IN_KEY',    'G/2z> ]NJe.K^Z3HtCF@ZFK8RZQ% . ~_g-/ur)a/`gUlr!j3=>?Br~pN@xWGbyR' );
define( 'NONCE_KEY',        'xSais)0^mDvW:xPZK05=|A:/-O=MJFsGSXK=`M{vt=dBkLP|gUr({bg&d:?t%f>)' );
define( 'AUTH_SALT',        'e%CwCQDWi G5q4MM^U|@:>7/D*-rsSWH[^sm%7!YV^E,a7XT.gZRO)xdJje7r?^9' );
define( 'SECURE_AUTH_SALT', ':sv@CPSPKBZ6f1|[S>hznCM<F(rXCtrSI]tm2&Hzn^[8sODMo1qfT~G$Ia8d?Hm/' );
define( 'LOGGED_IN_SALT',   'U#Imc~?P39.]%8FU[)3W6f_,V}qX5x!6mu.lx~)31F}ka36HRc#(|r_x))JNX!h_' );
define( 'NONCE_SALT',       'M=SZW5GU,rx`L&C5Q/{D1v12Z{o6I-ROrg_gR$fbm#zX5z:(,xQ}nVgd(gSP7[Qg' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
