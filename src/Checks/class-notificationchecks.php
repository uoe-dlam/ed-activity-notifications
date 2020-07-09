<?php declare(strict_types=1);

namespace EdAc\DLAM\BlogNotifier\Checks;

use EdAc\DLAM\BlogNotifier\BlogMeta\BlogMeta;
use WP_Comment;
use WP_Post;

/**
 * NotificationChecks.
 *
 * This class contains the checks we perform to verify a WordPress
 * action that requires a notification to be sent.
 *
 * @author    DLAM Applications Development Team <ltw-apps-dev@ed.ac.uk>
 * @copyright University of Edinburgh
 * @license   http://mit-license.org/
 *
 * @link https://github.com/uoe-dlam/ed-activity-notifications
 */
class NotificationChecks {

	/**
	 * The value when a comment has no parent comment.
	 */
	private const COMMENT_HAS_NO_PARENT = 0;

	/**
	 * Check that the Status from the action corresponds to something we handle
	 *
	 * @param string $new_status
	 * @param string $old_status
	 * @return boolean
	 */
	public function post_is_being_published( string $new_status, string $old_status ): bool {
		//Check if transition / make private is an issue
		$notifiable_new_statuses = array(
			'publish',
			'private',
		);

		return in_array( $new_status, $notifiable_new_statuses, true ) &&
			! in_array( $old_status, $notifiable_new_statuses, true );
	}

	/**
	 * Check that the post type from the action corresponds to something we handle
	 *
	 * @param WP_Post $post
	 * @return boolean
	 */
	public function post_type_is_post( WP_Post $post ): bool {
		return 'post' === $post->post_type;
	}

	/**
	 * Check that the blog for the post corresponds to something we handle
	 *
	 * @param BlogMeta $blog
	 * @return boolean
	 */
	public function is_student_blog( BlogMeta $blog ): bool {
		return $blog->is_student_blog();
	}

	/**
	 * Check that the submitted comment is a reply to another comment
	 *
	 * @param integer $comment_parent_id
	 * @return boolean
	 */
	public function is_a_response_comment( int $comment_parent_id ): bool {
		return self::COMMENT_HAS_NO_PARENT !== $comment_parent_id;
	}

	/**
	 * Check that the submitted comment is from the blog_owner, e.g. the student
	 *
	 * @param string $blog_creator_id
	 * @param integer $comment_author_id
	 * @return boolean
	 */
	public function is_reply_from_the_blog_owner( string $blog_creator_id, int $comment_author_id ): bool {
		return (int) $blog_creator_id === $comment_author_id;
	}

	/**
	 * Check that the parent comment is from the instructor user, based on the email we have.
	 *
	 * @param string $email
	 * @param integer|null $comment_parent_id
	 * @return boolean
	 */
	public function is_in_response_to_instructors_comment( string $email, int $comment_parent_id ): bool {

		$parent_comment = WP_Comment::get_instance( $comment_parent_id );

		if ( false === $parent_comment ) {
			//Cannot perform a meaningful check
			return false;
		}

		$parent_comment_author = get_user_by( 'id', $parent_comment->user_id );

		return ( 0 === \strcasecmp( $email, $parent_comment_author->user_email ) );
	}
}
