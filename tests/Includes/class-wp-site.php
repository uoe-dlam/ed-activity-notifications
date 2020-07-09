<?php declare(strict_types=1);

/**
 * A 'Mock' class, for testing the plugin
 */
if ( false === class_exists( 'WP_Site' ) ) {
	class WP_Site {
		public $blog_id;
		public function __construct( int $value ) {
			$this->blog_id = $value;
		}

		public function __get( string $name ) {
			return $this->blog_id;
		}
	}
};
