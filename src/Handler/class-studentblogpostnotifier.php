<?php declare(strict_types=1);

namespace EdAc\DLAM\BlogNotifier\Handler;

use WP_Post;

/**
 * StudentBlogPostNotifier.
 *
 * This class is registered to listen for the WordPress action,
 * perform the check to see if the action relates to
 * a student creating a blog post,
 * and if so notify the course instructor via email.
 *
 * @author    DLAM Applications Development Team <ltw-apps-dev@ed.ac.uk>
 * @copyright University of Edinburgh
 * @license   http://mit-license.org/
 *
 * @link https://github.com/uoe-dlam/ed-activity-notifications
 */
final class StudentBlogPostNotifier extends Handler {

	/**
	 * Register the handler for the WordPress action we listen for.
	 *
	 * @return void
	 */
	public static function register(): void {
		add_action( 'transition_post_status', array( self::class, 'handle' ), 10, 3 );
	}

	/**
	 * Static function to call when the action is triggered
	 *
	 * @param string $new_status
	 * @param string $old_status
	 * @param WP_Post $post
	 *
	 * @return void
	 */
	public static function handle( string $new_status, string $old_status, WP_Post $post ): void {
		( self::create() )->run( $new_status, $old_status, $post );
	}

	/**
	 * Check if the WordPress action relates to a
	 * student blog post being published,
	 * and if so, notify the instructor by email.
	 *
	 * @param string $new_status
	 * @param string $old_status
	 * @param WP_Post $post
	 *
	 * @return void
	 */
	public function run( string $new_status, string $old_status, WP_Post $post ): void {
		if ( true === $this->should_send( $new_status, $old_status, $post ) ) {
			$this->mailer->send_student_blog_posted_notification(
				$this->get_instructor_email(),
				get_permalink( $post ),
				\get_blog_details( array( 'blog_id' => $this->blog_meta->blog_id ) )
			);
		}
	}

	/**
	 * Performs the check to see if the email should be sent for this post
	 *
	 * @param string $new_status
	 * @param string $old_status
	 * @param WP_Post $post
	 *
	 * @return boolean
	 */
	private function should_send( string $new_status, string $old_status, WP_Post $post ): bool {

		if ( null === $this->blog_meta ) {
			return false;
		}

		if ( ! $this->checker->post_is_being_published( $new_status, $old_status ) ) {
			return false;
		}

		if ( ! $this->checker->post_type_is_post( $post ) ) {
			return false;
		}

		if ( ! $this->checker->is_student_blog( $this->blog_meta ) ) {
			return false;
		}

		return true;
	}
}
