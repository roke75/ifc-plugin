=== Instant Feedback Collector Plaugin ===
Contributors: your_username
Donate link: https://examplewebsite.com
Tags: survey, questionnaire, feedback, real-time, interactive
Requires at least: 5.0
Tested up to: 6.3
Requires PHP: 7.2
Stable tag: 1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Instant Feedback Collector Plaugin allows you to create and manage interactive surveys on your WordPress site, offering real-time results and a responsive user experience.

== Description ==

**Instant Feedback Collector Plaugin** is a powerful tool that enables you to create, manage, and display surveys on your WordPress site. The plugin offers the following features:

- **Real-Time Results:** Answers update automatically without page reloads.
- **Manage Questions and Answers:** Add, edit, and delete questions and related answers through an easy-to-use admin panel.
- **Responsive Design:** Answers are automatically arranged in a grid that adapts to different device sizes.
- **Animations:** New answers appear with smooth animations, enhancing user experience.
- **Security:** Utilizes nonces and input sanitization to protect your site.

**Use Cases:**

- Collect feedback from your users.
- Conduct polls and questionnaires.
- Add interactive elements to your website.

== Installation ==

1. **Download the plugin's zip file** to your computer.
2. **Navigate to your WordPress admin area** and go to 'Plugins' -> 'Add New' -> 'Upload Plugin'.
3. **Select the zip file** and click 'Install Now'.
4. **Activate the plugin** after installation.
5. **Configure the plugin** by going to the 'Instant Feedback Collector Plaugin' section in the WordPress admin.

== Frequently Asked Questions ==

= How do I add a new question? =

- Go to the 'Instant Feedback Collector Plaugin' section in your WordPress admin.
- Fill out the 'Add New Question' form and click 'Add Question'.
- The new question will appear in the question list, where you can find its ID number.

= How do I add a survey to a page? =

- Use the shortcode `[survey id="X"]`, where "X" is the question ID.
- Insert the shortcode into the desired page or post.

= How do I display answers on a page? =

- Use the shortcode `[survey_results id="X"]`, where "X" is the question ID.
- Insert the shortcode into the page where you want to display the answers.

= How are answers updated in real-time? =

- The plugin uses AJAX technology to automatically update answers every 5 seconds without reloading the page.

= How can I edit or delete questions and answers? =

- Navigate to the 'Instant Feedback Collector Plaugin' admin page.
- In the question list, you can edit or delete questions and remove their associated answers.

= Can I retain data when uninstalling the plugin? =

- Yes, you can choose whether to delete data upon plugin uninstallation in the settings.
- Go to the 'Instant Feedback Collector Plaugin' settings and select the appropriate option.

== Screenshots ==

1. **Creating a survey in the admin panel** - View of adding a new question.
2. **Survey form on a page** - Example of displaying a survey on a page.
3. **Real-time results** - Answers updating automatically in the grid.
4. **Admin page** - List of questions and actions.

== Changelog ==

= 1.0 =
* Initial release.
* Ability to create and manage questions.
* Display questions and collect answers on the site.
* Real-time answer updates on the results page.
* Responsive grid layout and animations for new answers.

== Upgrade Notice ==

= 1.0 =
* Initial release. Update to the latest version to get all features and improvements.

== Arbitrary section ==

### Upcoming Features

- **Multilingual Support:** Compatibility with WPML or Polylang plugins for multiple languages.
- **Reporting and Analytics:** Ability to export answers in CSV format and view statistics.
- **User Role Management:** More granular permissions for different user roles.

== License ==

This plugin is licensed under the GPLv2 or later. You are free to use, modify, and distribute the plugin under the terms of this license.

== Documentation ==

For more information and usage instructions, please visit:

[Plugin Documentation](https://github.com/roke75/ifc-plugin)

== Settings ==

The plugin settings can be found in the WordPress admin under 'Instant Feedback Collector Plaugin'. From there, you can:

- **Add new questions.**
- **Edit and delete existing questions.**
- **Delete answers related to questions.**
- **Choose whether to delete data upon plugin uninstallation.**

== Support ==

If you encounter issues or have questions, please contact us:

- **Email:** roke00@gmail.com
- **Website:** [https://examplewebsite.com](https://examplewebsite.com)
- **GitHub:** [https://github.com/roke75/ifc-plugin](https://github.com/roke75/ifc-plugin)

== After Installation ==

1. **Activate the plugin** from the WordPress admin.
2. **Go to 'Instant Feedback Collector Plaugin'** in the admin dashboard.
3. **Add a new question** and note its ID.
4. **Add the survey to a page** using the shortcode `[survey id="X"]`.
5. **Display the results** using the shortcode `[survey_results id="X"]`.
6. **Test the functionality** by submitting answers and observing the real-time updates on the results page.

== Security ==

- **Input Validation:** All user inputs are sanitized to ensure security.
- **Nonce Verification:** Forms and AJAX requests use nonces to prevent CSRF attacks.
- **Permissions:** Admin functions are only available to users with 'manage_options' capabilities.

== Compatibility ==

- **WordPress Versions:** Tested with WordPress versions 5.0 to 6.3.
- **PHP Versions:** Works with PHP versions 7.2 and above.
- **Themes and Plugins:** Designed to be compatible with most themes and plugins.

== For Developers ==

The plugin's source code is available on GitHub. We welcome contributions and suggestions for improvements.

- **GitHub Repository:** [https://github.com/roke75/ifc-plugin](https://github.com/roke75/ifc-plugin)

== Acknowledgements ==

Thank you to everyone who has supported the development of this plugin and provided feedback for its improvement.
