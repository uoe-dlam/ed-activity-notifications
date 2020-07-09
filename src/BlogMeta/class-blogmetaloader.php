<?php declare(strict_types=1);

namespace EdAc\DLAM\BlogNotifier\BlogMeta;

/**
 * BlogMetaLoader.
 *
 * Load data from the blogs meta table and populate a blog object with it.
 *
 * @author    DLAM Applications Development Team <ltw-apps-dev@ed.ac.uk>
 * @copyright University of Edinburgh
 * @license   https://www.gnu.org/licenses/gpl.html
 *
 * @link https://gitlab.is.ed.ac.uk/is-dlam/academic-blogging-project/ed-student-post-alerts
 */
class BlogMetaLoader {

	/**
	 * Load data from blogs meta for the requested blog
	 *
	 * @param int $blog_id
	 * @return BlogMeta|null
	 */
	public function load( int $blog_id ): ?BlogMeta {
		global $wpdb;

		$query = 'SELECT '
			. 'student.blog_id, '
			. 'student.course_id, '
			. 'student.blog_type, '
			. 'student.creator_id '
			. "FROM {$wpdb->base_prefix}blogs_meta AS student "
			. 'WHERE student.blog_id = %s ';

		$blog_meta_details = $wpdb->get_row(
			$wpdb->prepare(
				//PHPCS:Ignore WordPress.DB.PreparedSQL.NotPrepared
				$query,
				array(
					$blog_id,
				)
			)
		);

		if ( null === $blog_meta_details ) {
			return null;
		}

		return new BlogMeta( $blog_meta_details );
	}
}
