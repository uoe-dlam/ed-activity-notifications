<?php declare(strict_types=1);

namespace EdAc\DLAM\BlogNotifier\Utils;

use WP_Site;

/**
 * Mailer.
 *
 * Creates and sends the email to the course instructor to notify them of a new student blog post
 *
 * @author    DLAM Applications Development Team <ltw-apps-dev@ed.ac.uk>
 * @copyright University of Edinburgh
 * @license   https://www.gnu.org/licenses/gpl.html
 *
 * @link https://gitlab.is.ed.ac.uk/is-dlam/academic-blogging-project/ed-student-post-alerts
 */
class Mailer {

	/**
	 * Send a Notification to the Course Instructor
	 * that a blog post has been published.
	 *
	 * @param string $email
	 * @param string $post_url
	 * @param WP_Site $blog_details
	 *
	 * @return bool Whether the email contents were sent successfully.
	 */
	public function send_student_blog_posted_notification(
		string $email,
		string $post_url,
		WP_Site $blog_details
	): bool {

		if ( '' === $email ) {
			return false;
		}

		$to            = $email;
		$subject       = "New post published for the blog: '{$blog_details->blogname}'";
		$template_file = 'student-blog-posted-notification-message.html';

		//Message Body Params
		$body_params = array(
			'blog_name' => $blog_details->blogname,
			'post_url'  => $post_url,
		);

		return $this->send(
			$to,
			$subject,
			$template_file,
			$body_params
		);
	}

	/**
	 * Send a Notification to the Course Instructor
	 * that a response to their comment has been submitted.
	 *
	 * @param string $email
	 * @param string $post_url
	 * @param WP_Site $blog_details
	 *
	 * @return bool Whether the email contents were sent successfully.
	 */
	public function send_student_replied_to_comment_notification(
		string $email,
		string $post_url,
		WP_Site $blog_details
	): bool {

		if ( '' === $email ) {
			return false;
		}

		$to            = $email;
		$subject       = "New comment reply published for the blog: '{$blog_details->blogname}'";
		$template_file = 'student-reply-posted-notification-message.html';

		//Message Body Params
		$body_params = array(
			'blog_name' => $blog_details->blogname,
			'post_url'  => $post_url,
		);

		return $this->send(
			$to,
			$subject,
			$template_file,
			$body_params
		);
	}

	/**
	 *
	 * @param string $to
	 * @param string $subject
	 * @param string $template_file
	 * @param array $body_params
	 * @return boolean
	 */
	private function send(
		string $to,
		string $subject,
		string $template_file,
		array $body_params
	) : bool {

		$headers = 'Content-Type: text/html; charset=UTF-8';

		$message_body = \file_get_contents(
			__DIR__ .
			'/templates/' .
			$template_file
		);

		$mustache_value = static function ( string $value ) {
			return \sprintf( '{{%s}}', $value );
		};

		//Replace message params
		$message_body = str_replace(
			array_map( $mustache_value, array_keys( $body_params ) ),
			$body_params,
			$message_body
		);

		return wp_mail( $to, $subject, $message_body, $headers );
	}
}
