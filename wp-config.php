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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'query' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         '4NgU0ue.kpk%L*okTDN7QjzBmk0 OZ*S_E0y1MUra{:+LY$vmu!YD2+%62=%yp(s' );
define( 'SECURE_AUTH_KEY',  'oGEnyi~.:pJ>PU9efxB. zoMBg+jx8+oEC|scKo#YOZY!!m[31br;DF=&(1X_L4>' );
define( 'LOGGED_IN_KEY',    'A1_#-g~gOZ2[q%>}:Vo76>4N+BY)D9}wK9]Ahl>#>7nW%iJbil20>C?UBzs6uAU,' );
define( 'NONCE_KEY',        '5~B6YBvBV,X114eqv2i$(B,jRx-TKzh<Nx1mKM/B`:]1nnk;-<at/ro7QIL#D8/D' );
define( 'AUTH_SALT',        'JdYC@f^Ld!TVf}p*{G (ezP2?s,L c<~4EPiI;$pK3 GxT0}i/3;[Hq5$G+Hz5wW' );
define( 'SECURE_AUTH_SALT', '7D_qO1b,=Ar_>Iu$ey5>g*IX:%NVJhoWMT}l!Ve(&$:&7jH1nd>?gCdi:x6FlIJE' );
define( 'LOGGED_IN_SALT',   '9k_ e/3PgK@cjh3w)=5}eKknH:+hD}#H&o{>KPVb?o?p11C&d+rMz3=qo%;hWp&S' );
define( 'NONCE_SALT',       '{4+INqdQ?N@TNUa0(Wj>`w-:X0p6JgKq<DX *OD~C?9$FCc4T^o{PYWc5gV,7x`4' );

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
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false);

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
