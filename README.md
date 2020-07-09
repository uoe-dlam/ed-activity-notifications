## University of Edinburgh Wordpress Plugin - Blog Notification Alerts

### UoE DLAM Blog Notification Alerts

**Description**

This plugin is designed to listen for a blog events, via the wordpress action hooks,
check if they are notifiable, and if so, notify the course instructor, via email.

**Notifiable Events**

 - Student Publishes a Blog Post.
 - Student Replies to an Instructor's Comment on the Student's Blog Post.

**Instructor Email**

To identify the email for the course instructor, the code will check the following location.

 - 'notification_email' blog option as sent from the VLE module settings as a custom parameter.

----

**Run Tests**

Requires Composer dev dependencies to be installed.

Depending on your entry point, this will run the plugin unit tests within a wordpress installation.

```
wp-content/plugins/ed-student-post-alerts/vendor/bin/phpunit -c wp-content/plugins/ed-student-post-alerts/phpunit.xml --testsuite Unit
```
