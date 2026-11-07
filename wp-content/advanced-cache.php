<?php
/**
 * SG Page Cache — early-serve drop-in (wp-content/advanced-cache.php).
 *
 * WordPress includes this file in wp-settings.php when WP_CACHE is on, BEFORE the database,
 * plugins and mu-plugins load. It serves the exact cache files written by the SG Page Cache
 * mu-plugin (same on-disk format: 3-byte status prefix + HTML), so a warm page costs a file
 * read instead of a full WordPress bootstrap. It NEVER stores: storing, purging and every
 * other rule stay with the mu-plugin, which also remains the fallback server whenever this
 * drop-in declines (stale file, bypassed request, missing constant).
 *
 * The bypass rules and the cache-key recipe below intentionally mirror sgpc_bypass() and
 * sgpc_file() in mu-plugins/sg-page-cache.php — including core's wp_is_mobile() test, which
 * the mu-plugin uses for the device variant. Keep the two in sync when either changes.
 */

if ( ! defined( 'ABSPATH' ) ) { return; }

/* Only ever serve a plain front-end GET; everything else falls through to WordPress. */
if ( ! isset( $_SERVER['REQUEST_METHOD'] ) || 'GET' !== strtoupper( (string) $_SERVER['REQUEST_METHOD'] ) ) { return; }
if ( defined( 'WP_ADMIN' ) && WP_ADMIN ) { return; }
if ( ! isset( $_SERVER['REQUEST_URI'] ) ) { return; }

$sgac_uri = (string) $_SERVER['REQUEST_URI'];
if ( false !== strpos( $sgac_uri, '/wp-' ) || false !== strpos( $sgac_uri, 'preview' ) || false !== strpos( $sgac_uri, 'feed' ) ) { return; }
if ( isset( $_SERVER['QUERY_STRING'] ) && '' !== (string) $_SERVER['QUERY_STRING'] ) { return; }
foreach ( array_keys( $_COOKIE ) as $sgac_c ) {
	if ( preg_match( '/^(wordpress_logged_in|wp-postpass|comment_author)/', (string) $sgac_c ) ) { return; }
}
if ( isset( $_COOKIE['wordpress_test_cookie'] ) ) { return; }

/* Device variant — core wp_is_mobile()'s test, verbatim (the function itself is not loaded yet). */
$sgac_ua       = isset( $_SERVER['HTTP_USER_AGENT'] ) ? (string) $_SERVER['HTTP_USER_AGENT'] : '';
$sgac_mobile   = '' !== $sgac_ua && ( false !== strpos( $sgac_ua, 'Mobile' ) || false !== strpos( $sgac_ua, 'Android' )
	|| false !== strpos( $sgac_ua, 'Silk/' ) || false !== strpos( $sgac_ua, 'Kindle' )
	|| false !== strpos( $sgac_ua, 'BlackBerry' ) || false !== strpos( $sgac_ua, 'Opera Mini' )
	|| false !== strpos( $sgac_ua, 'Opera Mobi' ) );
$sgac_host     = isset( $_SERVER['HTTP_HOST'] ) ? strtolower( (string) $_SERVER['HTTP_HOST'] ) : '';
$sgac_dir      = defined( 'WP_CONTENT_DIR' ) ? WP_CONTENT_DIR : ABSPATH . 'wp-content';
$sgac_file     = $sgac_dir . '/cache/sg-page/' . md5( $sgac_host . $sgac_uri . ( $sgac_mobile ? '|m' : '|d' ) ) . '.html';
$sgac_ttl      = 1800; /* SGPC_TTL in the mu-plugin: 30 minutes. */

if ( is_readable( $sgac_file ) && ( time() - filemtime( $sgac_file ) ) < $sgac_ttl ) {
	$sgac_head = @file_get_contents( $sgac_file, false, null, 0, 3 );
	if ( '200' === $sgac_head ) {
		header( 'Content-Type: text/html; charset=UTF-8' );
		header( 'Vary: User-Agent' );   /* cache is keyed per device class; proxies must not mix them */
		header( 'X-SG-Cache: hit' );
		$sgac_fp = fopen( $sgac_file, 'rb' );
		if ( $sgac_fp ) {
			fseek( $sgac_fp, 3 );       /* skip the 3-byte status prefix */
			fpassthru( $sgac_fp );
			fclose( $sgac_fp );
		}
		exit;
	}
}
