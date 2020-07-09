<?php declare(strict_types=1);

/*
Plugin Name: UoE DLAM WordPress Student Blog Notifier
Description: Notify a course instructor when a student posts on their blog, or a student replies to a comment by the course instructor on the student's blog post.
Author: DLAM Applications Development Team
Version: 1.0.0
*/

namespace EdAc\DLAM\BlogNotifier;

// Include the autoloader so we can dynamically include the rest of the classes.
require_once trailingslashit( __DIR__ ) . 'inc/autoloader.php';

Handler\StudentBlogPostNotifier::register();
Handler\StudentReplyToInstructorCommentNotifier::register();
