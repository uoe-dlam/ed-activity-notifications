<?php declare(strict_types=1);

namespace EdAc\DLAM\BlogNotifier\Handler;

/**
 * StudentReplyToInstructorCommentNotifier.
 *
 * This class is registered to listen for the WordPress action,
 * perform the check to see if the action relates to
 * a student replying to an instructor's comment
 * on the student's blog post,
 * and if so notify the course instructor via email.
 *
 * @author    DLAM Applications Development Team <ltw-apps-dev@ed.ac.uk>
 * @copyright University of Edinburgh
 * @license   https://www.gnu.org/licenses/gpl.html
 *
 * @link https://gitlab.is.ed.ac.uk/is-dlam/academic-blogging-project/ed-student-post-alerts
 */
final class StudentReplyToInstructorCommentNotifier extends Handler {

	/**
	 * Register the handler for the WordPress action we listen for.
	 *
	 * @return void
	 */
	public static function register() : void {
		add_action( 'comment_post', array( self::class, 'handle' ), 10, 3 );
	}

	/**
	 * Static function to call when the action is triggered
	 *
	 * @param int $comment_id
	 * @param bool $comment_approved
	 * @param array $comment_data
	 *
	 * @return void
	 */
	public static function handle( int $comment_id, bool $comment_approved, array $comment_data ): void {
		( self::create() )->run( $comment_id, $comment_data );
	}

	/**
	 * Check if the WordPress action to a student replying
	 * to an instructor's comment on the student's blog post,
	 * and if so, notify the instructor by email.
	 *
	 * @param int $comment_id
	 * @param array $comment_data
	 * @return void
	 */
	public function run( int $comment_id, array $comment_data ): void {
		if ( true === $this->should_send( $comment_data ) ) {
			$this->mailer->send_student_replied_to_comment_notification(
				$this->get_instructor_email(),
				get_permalink( $comment_data['comment_post_ID'] ),
				get_blog_details( array( 'blog_id' => $this->blog_meta->blog_id ) )
			);
		}
	}

	/**
	 * Perform the checks to determine if this is an notifiable action
	 *
	 * @param array $comment_data
	 * @return boolean
	 */
	public function should_send( array $comment_data ): bool {

		if ( null === $this->blog_meta ) {
			return false;
		}

		if ( ! $this->checker->is_student_blog( $this->blog_meta ) ) {
			return false;
		}

		if ( ! $this->checker->is_a_response_comment( $comment_data['comment_parent'] ) ) {
			return false;
		}

		if ( ! $this->checker->is_reply_from_the_blog_owner( $this->blog_meta->creator_id, $comment_data['user_id'] ) ) {
			return false;
		}

		if ( ! $this->checker->is_in_response_to_instructors_comment(
			$this->get_instructor_email(),
			$comment_data['comment_parent']
		) ) {
			return false;
		}

		return true;
	}
}
