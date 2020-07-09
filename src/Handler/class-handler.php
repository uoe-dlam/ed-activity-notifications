<?php declare(strict_types=1);

namespace EdAc\DLAM\BlogNotifier\Handler;

use EdAc\DLAM\BlogNotifier\BlogMeta\BlogMeta;
use EdAc\DLAM\BlogNotifier\BlogMeta\BlogMetaLoader;
use EdAc\DLAM\BlogNotifier\Checks\NotificationChecks;
use EdAc\DLAM\BlogNotifier\Utils\Mailer;

/**
 * Handler.
 *
 * An abstract class for the handler, to hold common functions.
 *
 * @author    DLAM Applications Development Team <ltw-apps-dev@ed.ac.uk>
 * @copyright University of Edinburgh
 * @license   http://mit-license.org/
 *
 * @link https://github.com/uoe-dlam/ed-activity-notifications
 */
abstract class Handler {

	/**
	 * @var NotificationChecks
	*/
	protected $checker;

	/**
	 * @var BlogMetaLoader
	 */
	protected $blog_meta_loader;

	/**
	 * @var Mailer
	 */
	protected $mailer;

	/**
	 * @var BlogMeta
	 */
	protected $blog_meta;

	/**
	 *
	 * @param NotificationChecks $checker
	 * @param BlogMetaLoader $blog_meta_loader
	 * @param Mailer $mailer
	 *
	 */
	public function __construct(
		NotificationChecks $checker,
		BlogMetaLoader $blog_meta_loader,
		Mailer $mailer
	) {
		$this->checker          = $checker;
		$this->blog_meta_loader = $blog_meta_loader;
		$this->mailer           = $mailer;

		$this->blog_meta =
			$this->blog_meta_loader->load(
				$this->get_current_blog_id()
			);
	}

	/**
	 * Instantiate a new handler
	 *
	 * @return self
	 */
	public static function create(): self {
		return  new static(
			new NotificationChecks(),
			new BlogMetaLoader(),
			new Mailer()
		);
	}

	/**
	 * Find the instructor email from the blog option, if set
	 *
	 * @return string ('' if not found)
	 */
	protected function get_instructor_email(): string {
		return get_blog_option( $this->blog_meta->blog_id, 'notification_email', '' );
	}

	/**
	 * Get the current blog id
	 *
	 * @return int The current blog id
	 */
	protected function get_current_blog_id(): int {
		$blog_details = \get_blog_details();  //No params will return current blog
		return $blog_details->__get( 'id' );
	}
}
