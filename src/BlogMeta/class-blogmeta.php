<?php declare(strict_types=1);

namespace EdAc\DLAM\BlogNotifier\BlogMeta;

/**
 * BlogMeta.
 *
 * A class for holding the blog meta data needed for this plugin
 *
 * @author    DLAM Applications Development Team <ltw-apps-dev@ed.ac.uk>
 * @copyright University of Edinburgh
 * @license   https://www.gnu.org/licenses/gpl.html
 *
 * @link https://gitlab.is.ed.ac.uk/is-dlam/academic-blogging-project/ed-student-post-alerts
 */
class BlogMeta {

	public $blog_id;
	public $blog_type;
	public $course_id;
	public $creator_id;

	public function __construct( $data ) {
		$this->blog_id    = $data->blog_id ?? null;
		$this->blog_type  = $data->blog_type ?? null;
		$this->course_id  = $data->course_id ?? null;
		$this->creator_id = $data->creator_id ?? null;
	}

	public function is_student_blog(): bool {
		return 'student' === $this->blog_type;
	}
}
