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

define( 'DB_NAME', 'emailwire' );



/** MySQL database username */

define( 'DB_USER', 'root' );



/** MySQL database password */

define( 'DB_PASSWORD', 'Parveen@123' );



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

define( 'AUTH_KEY',         '*JxleC5/PN;_m-8f^<y~SX$3r ,SU8CswN*>S=`/rqGg%AbXe|6;#r% 9H)]8;X^' );

define( 'SECURE_AUTH_KEY',  'YOGB20!zQw^,17HVj&K&<NB<N0IvqLJ2O6( MMPo}rBMa7[{;oNDbHPRj`!lB3uw' );

define( 'LOGGED_IN_KEY',    '4z4y=E5Rl.r?T2U6}cMZ&A|H<T>t~:- 3y!e?,j5%$z.l,==Hz.G-Q(KvUJ3SqR=' );

define( 'NONCE_KEY',        '9z%)Mo+nR,9j6-06dYe4{^gz(uw{b{GNYTF|xsS*qJPX/;|Xfk&P5|;Bao:+~W;N' );

define( 'AUTH_SALT',        'm5dOrGH$Le]eLCv_sBlY~!6ibA7,C%&&r;`g:IV[$x|{Rtt>OwQdn8hJ>XGZX)sc' );

define( 'SECURE_AUTH_SALT', 'bAwEu x_p-Io!0:y&7}+!GDB.YK-Gl}Z<rf8=}>>Mn&TAPu6A7=TXv5eILNaR|];' );

define( 'LOGGED_IN_SALT',   'N9<2K{s9eewt]jP8;GD7h,8T/!i6zE|=2zfqvP9jyyU>^Ln*[%Uf&;L=Vw`?:q39' );

define( 'NONCE_SALT',       'Z:L%sK]#3XNE-BlX~+`q_&umgdoEs#D++N*yD;*yst VhEvD 7J k!]Wfu$ju?[G' );



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

