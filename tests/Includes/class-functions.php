<?php declare(strict_types=1);

if ( false === function_exists( 'get_blog_details' ) ) {
	function get_blog_details() {
		return new \WP_Site( 1234 );
	}
}

if ( false === function_exists( 'get_blog_option' ) ) {
	function get_blog_option() {
		return '';
	}
}

if ( false === function_exists( 'untrailingslashit' ) ) {
	function untrailingslashit( $string ) {
		return rtrim( $string, '/\\' );
	}
}

if ( false === function_exists( 'trailingslashit' ) ) {
	function trailingslashit( $string ) {
		return untrailingslashit( $string ) . '/';
	}
}

if ( false === function_exists( 'get_permalink' ) ) {
	function get_permalink( $post = 0, $leavename = false ) {
		return '';
	}
}
