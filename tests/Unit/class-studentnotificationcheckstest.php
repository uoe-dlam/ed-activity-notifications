<?php declare(strict_types=1);

namespace Tests\Unit;

use EdAc\DLAM\BlogNotifier\BlogMeta\BlogMeta;
use EdAc\DLAM\BlogNotifier\Checks\NotificationChecks;

use PHPUnit\Framework\TestCase;

/**
 * NotificationChecksTest
 *
 * Unit Tests for NotificationChecks
 *
 * @author    DLAM Applications Development Team <ltw-apps-dev@ed.ac.uk>
 * @copyright University of Edinburgh
 * @license   https://www.gnu.org/licenses/gpl.html
 *
 * @link https://gitlab.is.ed.ac.uk/is-dlam/academic-blogging-project/ed-student-post-alerts
 */
class NotificationChecksTest extends TestCase {

	/**
	 * @var NotificationChecks
	 */
	private $subject;

	public static function setUpBeforeClass(): void {
		require_once dirname( __FILE__, 2 ) . '/Includes/class-wp-post.php';
		require_once dirname( __FILE__, 2 ) . '/Includes/class-functions.php';
	}

	public function setUp(): void {
		$this->subject = new NotificationChecks();
	}

	/**
	 * Test that the status check function works as expected
	 *
	 * @dataProvider statusCheckProvider
	 *
	 * @param boolean $expected_result
	 * @param string $new_status
	 * @param string $old_status
	 * @return void
	 */
	public function testPostPublishedStatusChecks(
		bool $expected_result,
		string $new_status,
		string $old_status
	): void {
		$this->assertEquals(
			$expected_result,
			$this->subject->post_is_being_published( $new_status, $old_status )
		);
	}

	public function statusCheckProvider(): array {
		return array(
			'new status set to private from a random status returns true' => array( true, 'private', 'random' ),
			'status changing from private to private returns false' => array( false, 'private', 'private' ),
			'status changing from publish to private returns false' => array( false, 'publish', 'private' ),
			'status changing from private to publish returns false' => array( false, 'private', 'publish' ),
		);
	}

	/**
	 * Test that the status check function works as expected
	 *
	 * @dataProvider postTypeProvider
	 *
	 * @param boolean $expected_result
	 * @param string $post_value
	 * @return void
	 */
	public function testPostTypeChecks(
		bool $expected_result,
		string $post_value
	): void {
		$post = new \WP_Post( $post_value );

		$this->assertEquals(
			$expected_result,
			$this->subject->post_type_is_post( $post )
		);
	}

	public function postTypeProvider(): array {
		return array(
			'post type as post returns true'     => array( true, 'post' ),
			'post type as xxx returns false'     => array( false, 'xxx' ),
			'post type as notpost returns false' => array( false, 'notpost' ),
		);
	}

	/**
	 * Test that the is student block check function works as expected
	 *
	 * @dataProvider checkStudentBlogProvider
	 *
	 * @param boolean $expected_result
	 * @param string $blog_type
	 * @return void
	 */
	public function testStudentBlogChecks(
		bool $expected_result,
		string $blog_type
	): void {

		$this->assertEquals(
			$expected_result,
			$this->subject->is_student_blog(
				new BlogMeta(
					new class($blog_type) {
						public $blog_type;
						public function __construct( string $value ) {
							$this->blog_type = $value;
						}
					}
				)
			)
		);

	}

	public function checkStudentBlogProvider(): array {
		return array(
			'student blog type returns true' => array( true, 'student' ),
			'course blog type returns false' => array( false, 'course' ),
			'other blog type returns false'  => array( false, 'other' ),
		);
	}
}
