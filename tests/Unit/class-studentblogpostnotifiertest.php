<?php declare(strict_types=1);

namespace Tests\Unit;

use EdAc\DLAM\BlogNotifier\BlogMeta\BlogMeta;
use EdAc\DLAM\BlogNotifier\BlogMeta\BlogMetaLoader;
use EdAc\DLAM\BlogNotifier\Checks\NotificationChecks;
use EdAc\DLAM\BlogNotifier\Handler\StudentBlogPostNotifier;
use EdAc\DLAM\BlogNotifier\Utils\Mailer;

use PHPUnit\Framework\TestCase;

/**
 * StudentBlogPostNotifierTest
 *
 * Unit Tests for StudentBlogPostNotifier
 *
 * @author    DLAM Applications Development Team <ltw-apps-dev@ed.ac.uk>
 * @copyright University of Edinburgh
 * @license   https://www.gnu.org/licenses/gpl.html
 *
 * @link https://gitlab.is.ed.ac.uk/is-dlam/academic-blogging-project/ed-student-post-alerts
 */
class StudentBlogPostNotifierTest extends TestCase {

	/**
	 * @var MockObject|NotificationChecks
	*/
	private $checker;

	/**
	 * @var MockObject|BlogMeta
	 */
	private $blog_meta;

	/**
	 * @var MockObject|BlogMetaLoader
	 */
	private $blog_meta_loader;

	/**
	 * @var MockObject|Mailer
	 */
	private $mailer;

	/**
	 * @var string
	 */
	private $new_status;

	/**
	 * @var string
	 */
	private $old_status;

	/**
	 * @var WP_Post
	 */
	private $post;

	public function setUp(): void {

		require_once dirname( __FILE__, 2 ) . '/Includes/class-wp-post.php';
		require_once dirname( __FILE__, 2 ) . '/Includes/class-wp-site.php';
		require_once dirname( __FILE__, 2 ) . '/Includes/class-functions.php';

		$this->checker          = $this->createMock( NotificationChecks::class );
		$this->blog_meta_loader = $this->createMock( BlogMetaLoader::class );
		$this->mailer           = $this->createMock( Mailer::class );

		$this->new_status = 'new_status';
		$this->old_status = 'old_status';

		$this->post = new \WP_Post( 'not_post' );

		$this->blog_meta = $this->createMock( BlogMeta::class );
	}

	public function testRunNotifierPostIsNotBeingPublished(): void {

		$this->blog_meta_loader->expects( $this->once() )
			->method( 'load' )
			->willReturn( $this->blog_meta );

		$this->checker->expects( $this->once() )
				->method( 'post_is_being_published' )
				->with( $this->new_status, $this->old_status )
				->willReturn( false );

		$this->checker->expects( $this->never() )
				->method( 'post_type_is_post' );

		( new StudentBlogPostNotifier(
			$this->checker,
			$this->blog_meta_loader,
			$this->mailer
		) )->run( $this->new_status, $this->old_status, $this->post );
	}

	public function testRunNotifierPostIsNotCorrectType(): void {

		$this->blog_meta_loader->expects( $this->once() )
			->method( 'load' )
			->willReturn( $this->blog_meta );

		$this->checker->expects( $this->once() )
				->method( 'post_is_being_published' )
				->with( $this->new_status, $this->old_status )
				->willReturn( true );

		$this->checker->expects( $this->once() )
				->method( 'post_type_is_post' )
				->with( $this->post )
				->willReturn( false );

		$this->blog_meta_loader->expects( $this->once() )
				->method( 'load' );

		( new StudentBlogPostNotifier(
			$this->checker,
			$this->blog_meta_loader,
			$this->mailer
		) )->run( $this->new_status, $this->old_status, $this->post );
	}

	public function testRunNotifierPostIsNotStudentBlog(): void {

		$this->blog_meta_loader->expects( $this->once() )
			->method( 'load' )
			->willReturn( $this->blog_meta );

		$this->checker->expects( $this->once() )
				->method( 'post_is_being_published' )
				->with( $this->new_status, $this->old_status )
				->willReturn( true );

		$this->checker->expects( $this->once() )
				->method( 'post_type_is_post' )
				->with( $this->post )
				->willReturn( true );

		$this->blog_meta_loader->expects( $this->once() )
				->method( 'load' )
				->with( 1234 )
				->willReturn( $this->blog_meta );

		$this->checker->expects( $this->once() )
				->method( 'is_student_blog' )
				->with( $this->blog_meta )
				->willReturn( false );

		( new StudentBlogPostNotifier(
			$this->checker,
			$this->blog_meta_loader,
			$this->mailer
		) )->run( $this->new_status, $this->old_status, $this->post );
	}

	public function testRunNotifierWithNotifyAdminBlog(): void {

		$this->checker->expects( $this->once() )
				->method( 'post_is_being_published' )
				->with( $this->new_status, $this->old_status )
				->willReturn( true );

		$this->checker->expects( $this->once() )
				->method( 'post_type_is_post' )
				->with( $this->post )
				->willReturn( true );

		$this->blog_meta_loader->expects( $this->once() )
				->method( 'load' )
				->willReturnOnConsecutiveCalls(
					$this->blog_meta,
					null
				);

		$this->checker->expects( $this->once() )
				->method( 'is_student_blog' )
				->with( $this->blog_meta )
				->willReturn( true );

		$this->mailer->expects( $this->once() )
			->method( 'send_student_blog_posted_notification' );

		( new StudentBlogPostNotifier(
			$this->checker,
			$this->blog_meta_loader,
			$this->mailer
		) )->run( $this->new_status, $this->old_status, $this->post );
	}
}
