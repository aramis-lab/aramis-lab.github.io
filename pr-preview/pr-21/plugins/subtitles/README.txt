=== Subtitles ===
Contributors: wecobble, philiparthurmoore
Donate link: https://www.paypal.me/wecobble
Tags: subtitle, subtitles, title, titles
Requires at least: 3.9
Tested up to: 4.8.1
Requires PHP: 5.6
Stable tag: 3.0.0
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add subtitles into your WordPress posts, pages, custom post types, and themes. No coding required. Simply activate Subtitles and you're ready.

== Description ==

Right now WordPress currently presents no easy way for web publishers to add subtitles into their posts, pages, and other custom post types. This leaves users and developers in a bit of a quandary, trying to figure out how best to present subtitles in a beautiful and sensible way. Post [excerpts](http://codex.wordpress.org/Function_Reference/the_excerpt) are a very poor choice for subtitles and the only available option outside of [custom fields](http://codex.wordpress.org/Custom_Fields), but custom fields aren't entirely self-explanatory or user-friendly. This simple, straightforward plugin aims to solve this issue.

Simply download *Subtitles*, activate it, and begin adding subtitles into your posts and pages today. For more advanced usage of the plugin, please see the [Frequently Asked Questions](http://wordpress.org/plugins/subtitles/faq/).

If you like *Subtitles*, [thank me with coffee](https://www.paypal.me/wecobble). If you find it buggy, [tell me on GitHub](https://github.com/wecobble/Subtitles/issues). And if you have a cool example of how you're using *Subtitles* on your website, let me know on [Twitter](https://twitter.com/philip_arthur).

== Installation ==

By default the *Subtitles* plugin just works. All you should need to do in order to begin using it is activate the plugin and begin adding subtitles into your posts, pages, and *Subtitles*-enabled custom post types.

There are no custom template tags to add into your theme and, outside of advanced use, there is nothing you need to do to your theme in order to begin using this plugin.

What follows are instructions on how to install the plugin and get it working.

= Using The WordPress Dashboard (Recommended) =

1. Navigate to *Plugins → Add New* from within the WordPress Dashboard.
2. Search for `subtitles`.
3. Click **Install Now** on *Subtitles* by Philip Arthur Moore.
4. Activate the plugin.

= Uploading in WordPress Dashboard =

1. Navigate to *Plugins → Add New* from within the WordPress Dashboard.
2. Click on the **Upload** link underneath the *Install Plugins* page title.
3. Click the **Browse...** button and choose `subtitles.zip` in its download location on your computer.
4. Click the **Install Now** button.
5. Activate the plugin.

= Using FTP (Not Recommended) =

1. Download `subtitles.zip`.
2. Extract the `subtitles` directory to your computer.
3. Upload the `subtitles` directory to your `/wp-content/plugins/` directory.
4. Navigate to *Plugins → Installed Plugins* and activate the plugin.

== Frequently Asked Questions ==

There are two types of questions that are anticipated: user questions and developer questions. I've addressed all of them on GitHub. Please hop over to the *Subtitles* [FAQ on GitHub](https://github.com/wecobble/Subtitles#frequently-asked-questions) for more information about using the plugin.

== Screenshots ==

1. The input prompt for subtitles.
2. The front end view of a blog post with a subtitle.
3. Subtitles are shown in post and page pages in the Dashboard.

== Changelog ==

= v3.0.0 (August 8th, 2017) =

* Version Bump: Fix all WordPress Coding Standards errors and update Tested Up To version.
* Patch: Transfer ownership of plugin to [We Cobble](https://wecobble.com/)
* Patch: Change plugin donation link to point to We Cobble PayPal account.

= v2.2.1 (June 13th, 2016) =

* Version Bump: Fix wonky 2.2.0 release. No changes here; just a version bump to fix the last release package.

= v2.2.0 (June 13th, 2016) =

* Extra: Allow theme and plugin authors to override the early return if no subtitle exists (see [issue](https://github.com/wecobble/Subtitles/issues/79)).
* Extra: Automatically enable subtitles support for Jetpack Testimonials (see [issue](https://github.com/wecobble/Subtitles/issues/78)).
* Patch: Remove French language packs so that they are able to be directly pulled from WordPress.org (see [issue](https://wordpress.org/support/topic/french-translation-updated-3)).
* Patch: Change jetpack.me links to jetpack.com.
* Patch: Change plugin donation link to point to Professional Themes PayPal account.

= v2.1.1 (December 9th, 2015) =

* Bug Fix: Remove redundant htmlspecialchars from admin input (see [issue](https://github.com/wecobble/Subtitles/issues/66])).
* Patch: Transfer ownership of plugin to Professional Themes.
* Patch: Give developers the option to show subtitles in RSS feeds (see [issue](https://github.com/wecobble/Subtitles/issues/61)).
* Extra: Lithuanian (lt_LT) language packs added.

= v2.1.0 (July 20th, 2015) =

* Extra: Add a Subtitle column into the Posts and Pages admin screens.
* Extra: We have added in a way for developers to allow more tags in subtitles input.
* Extra: Update plugin POT.
* Patch: Remove font sizing from hidden entry subtitle in comments area (see [issue](https://github.com/wecobble/Subtitles/issues/46])).

= v2.0.1 (November 6th, 2014) =

* Bug Fix: Do not show subtitles in RSS feeds (see [issue](https://github.com/wecobble/Subtitles/issues/32)).
* Extra: Russian (ru_RU) language packs added
* Extra: Better WordPress Coding Standards
* Extra: WordPress 4.1 introduced a new hook called `edit_form_before_permalink` that allows us to move Subtitles into a more natural position, just underneath the post title. Let's use that and preserve backwards compatibility for older versions of WordPress (see [issue](https://github.com/wecobble/Subtitles/issues/30)).

= v2.0.0 (September 7th, 2014) =

* Performance Fix: Better CSS Handling for better overall plugin performance (see [issue](https://github.com/wecobble/Subtitles/issues/28)).

= v1.0.7 (August 17th, 2014) =

* Bug Fix: Better backend tabbing from the title to the subtitle input field (see [issue](https://github.com/wecobble/Subtitles/issues/23)).
* Extra: Add default support for Jetpack Portfolios (see [issue](https://github.com/wecobble/Subtitles/issues/26)).

= v1.0.6 (August 4th, 2014) =

* Bug Fix: Better visual styling in the back end to keep up with WordPress 4.0

= v1.0.5 (July 7th, 2014) =

* Bug Fix: If subtitles are shown in comment areas, we'll hide them by default.
* Bug Fix: Better security for nonce checking after update to the WordPress VIP Coding Standards. See [this discussion](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/190) for more information.
* Extra: Wrap primary entry title parts in spans that theme authors can take advantage of for more fine-grained styling when a post has a subtitle.
* Extra: French (fr_FR) language packs added (see [issue](https://github.com/wecobble/Subtitles/pull/18)).

= v1.0.4 (June 20th, 2014) =

* Bug Fix: Make sure that other plugins that try to mess with titles do not cause _Subtitles_ to throw PHP warnings due to the second optional `$id` parameter not being sent to the primary `the_subtitles` method used throughout sites (see [issue](https://github.com/wecobble/Subtitles/issues/16)).

= v1.0.3 (June 19th, 2014) =

* Bug Fix: Ensure that _Subtitles_ works in PHP 5.2.4 environments (see [issue](https://github.com/wecobble/Subtitles/issues/8)).

= v1.0.2 (June 18th, 2014) =

* Bug Fix: Check if `$post` is set before proceeding with any title filtering for subtitles (see [issue](https://github.com/wecobble/Subtitles/issues/12)).
* Bug Fix: Add a single space between titles and subtitles so that they look sensible when being output as a title attribute (see [commit](https://github.com/wecobble/Subtitles/commit/5b54263fcf82de6db9e7e0875a0a99974758a81f)).
* Extra: Catalan (ca) language packs added (see [issue](https://github.com/wecobble/Subtitles/pull/11)).
* Extra: Korean (ko_KR) language packs added (see [issue](https://github.com/wecobble/Subtitles/pull/10)).
* Extra: Spanish (es_ES) language packs added (see [issue](https://github.com/wecobble/Subtitles/pull/11)).
* Extra: Begin preparing plugin for better automated testing via [Travis CI](https://travis-ci.org/), [phpunit](https://github.com/sebastianbergmann/phpunit/), [WordPress Coding Standards](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards), and [CodeSniffer](http://pear.php.net/package/PHP_CodeSniffer/)

= v1.0.1 (June 14th, 2014) =

* Bug Fix: Make sure that the plugin automatically works with `single_post_title` (see [issue](https://github.com/wecobble/Subtitles/issues/2)).
* Bug Fix: Ensure that special characters in post titles do not erroneously cause subtitles to be skipped during title filtering and checks (see [issue](https://github.com/wecobble/Subtitles/issues/3)).
* Bug Fix: Remove unnecessary ID checks against nav menus (see [issue](https://github.com/wecobble/Subtitles/issues/4)).
* Bug Fix: Resolve title output issues when [WordPress SEO by Yoast](https://wordpress.org/plugins/wordpress-seo/) breadcrumbs are used inside of [The Loop](http://codex.wordpress.org/The_Loop) (see [issue](https://github.com/wecobble/Subtitles/issues/5)).
* Extra: Vietnamese (vi_VN) language packs added.
* Extra: German (de_DE) language packs added.
* Extra: Finnish (fi) language packs added.
* Extra: Italian (it_IT) language packs added.
* Extra: Japanese (ja) language packs added.

= v1.0.0 (June 12th, 2014) =
* Initial Release ([Launch Announcement](https://philiparthurmoore.com/subtitles))

== Upgrade Notice ==

= v3.0.0 (August 8th, 2017) =

* Version Bump: Fix all WordPress Coding Standards errors and update Tested Up To version.
* Patch: Transfer ownership of plugin to [We Cobble](https://wecobble.com/)
* Patch: Change plugin donation link to point to We Cobble PayPal account.
