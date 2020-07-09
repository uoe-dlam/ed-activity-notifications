<?php declare(strict_types=1);

/**
 * A 'Mock' class, for testing the plugin
 */
if ( false === class_exists( 'WP_Post' ) ) {
	class WP_Post {
		public $post_type;
		public function __construct( string $value ) {
			$this->post_type = $value;
		}
	}
};
