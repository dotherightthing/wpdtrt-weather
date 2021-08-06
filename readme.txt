
=== DTRT Weather ===
Contributors: dotherightthingnz
Donate link: http://dotherightthing.co.nz
Tags: weather, forecast, GPS
Requires at least: 5.3.3
Tested up to: 5.3.3
Requires PHP: 7.2.15
Stable tag: 0.3.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Displays historical weather information for the GPS location determined by the Featured Image.

== Description ==

Displays historical weather information for the GPS location determined by the Featured Image.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/wpdtrt-weather` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->DTRT Weather screen to configure the plugin

== Frequently Asked Questions ==

See [WordPress Usage](README.md#wordpress-usage).

== Screenshots ==

1. The caption for ./images/screenshot-1.(png|jpg|jpeg|gif)
2. The caption for ./images/screenshot-2.(png|jpg|jpeg|gif)

== Changelog ==

= 0.3.9 =
* [6ebed22] Don't load generic wpdtrt-scss styles in plugins (dotherightthing/wpdtrt-scss#1)

= 0.3.8 =
* [91cfd2f] Update wpdtrt-scss to 0.1.17
* [29caa49] Update wpdtrt-scss to 0.1.14
* [ac131a6] Update wpdtrt-scss to 0.1.13

= 0.3.7 =
* [a4f2853] Update dependencies, update wpdtrt-plugin-boilerplate from 1.7.16 to 1.7.17
* [03c8132] Sync comment with plugin generator

= 0.3.6 =
* [a4a0a9e] Update wpdtrt-plugin-boilerplate from 1.7.15 to 1.7.16
* [be8e8af] Remove redundant classes

= 0.3.5 =
* [9127d11] Docs
* [ce66239] Update wpdtrt-npm-scripts to 0.3.30
* [114b26a] Update dependencies
* [f699df5] Update wpdtrt-scss
* [9f6b174] Update wpdtrt-plugin-boilerplate from 1.7.14 to 1.7.15
* [8afe72c] Update wpdtrt-plugin-boilerplate from 1.7.13 to 1.7.14
* [8bee083] Update wpdtrt-plugin-boilerplate from 1.7.12 to 1.7.13
* [c3f3120] Fix documented path to CSS variables
* [5e687f2] Add placeholders for string replacements
* [209aac1] Load boilerplate JS, as it is not compiled by the boilerplate

= 0.3.4 =
* [6815c6f] Update wpdtrt-plugin-boilerplate from 1.7.7 to 1.7.12
* [fdbd778] Add missing argument
* [8c7d4db] Move styles to wpdtrt-scss
* [e1d995c] Ignore files sources from wpdtrt-npm-scripts

= 0.3.3 =
* [7a7ad55] Update dependencies, incl wpdtrt-plugin-boilerplate from 1.7.6 to 1.7.7 to use Composer v1
* [d25a6c2] Update wpdtrt-plugin-boilerplate from 1.7.5 to 1.7.6 to fix saving of admin field values

= 0.3.2 =
* Use CSS variables, compile CSS variables to separate file
* Update wpdtrt-npm-scripts to fix release
* Update wpdtrt-plugin-boilerplate to 1.7.5 to support CSS variables
* Disable widget (dotherightthing/wpdtrt-plugin-boilerplate#183)

= 0.3.1 =
* Docs
* Return data as an array (but see dotherightthing/wpdtrt-dbth#42)
* Fix typos
* set_plugin_data expects an array
* Update required WP and PHP versions

= 0.3.0 =
* Optimise breakpoints
* Remove redundant number parameter from shortcode
* Remove widget 'element' select as this plugin doesn't use widgets and no option is marked as the default
* Load PHP dependencies via composer rather than NPM, to resolve 'npm ERR! premature close'
* Fix/ignore linting errors
* Remove version requirement from weather-icons package, as it wasn't installing
* Replace Gulp build scripts with wpdtrt-npm-scripts
* Fix casing of Composer dependency
* Replace Travis with Github Actions
* Update wpdtrt-plugin-boilerplate to 1.7.0

= 0.2.2 =
* Update wpdtrt-plugin-boilerplate to 1.5.3
* Sync with generator-wpdtrt-plugin-boilerplate 0.8.2

= 0.2.1 =
* Update wpdtrt-exif dependency

= 0.2.0 =
* Update wpdtrt-plugin-boilerplate to 1.5.0
* Sync with generator-wpdtrt-plugin-boilerplate 0.8.0

= 0.1.8 =
* Update wpdtrt-plugin-boilerplate to 1.4.39
* Sync with generator-wpdtrt-plugin-boilerplate 0.7.27

= 0.1.7 =
* Update wpdtrt-plugin-boilerplate to 1.4.38
* Sync with generator-wpdtrt-plugin-boilerplate 0.7.25

= 0.1.6 =
* Update TGMPA dependency version for wpdtrt-exif

= 0.1.5 =
* Update wpdtrt-plugin-boilerplate to 1.4.25
* Sync with generator-wpdtrt-plugin-boilerplate 0.7.20 

= 0.1.4 =
* Update wpdtrt-exif to 0.1.8 in TGMPA config

= 0.1.3 =
* Update wpdtrt-exif to 0.1.8
* Update wpdtrt-plugin-boilerplate to 1.4.24

= 0.1.2 =
* Update wpdtrt-plugin to wpdtrt-plugin-boilerplate
* Update wpdtrt-plugin-boilerplate to 1.4.22
* Allow dev packages for wpdtrt-exif dependency

= 0.1.1 =
* Update wpdtrt-plugin to 1.4.15

= 0.1.0 =
* Update wpdtrt-plugin to 1.4.14
* Bump to the minimum version that can be updated by Composer

= 0.0.8 =
* Update string in test case

= 0.0.7 =
* Remove stray comma

= 0.0.6 =
* Demote dotherightthing/wpdtrt-exif to require-dev (test dependency)
* Handle Weather_Icon_Forecast as an object containing arrays, not an array
* Handle missing $post object on Settings page
* Fix path to autoloader when loading test dependencies or when loaded as a test dependency

= 0.0.5 =
* Update wpdtrt-plugin to 1.4.6

= 0.0.4 =
* Fix interaction with wpdtrt-exif

= 0.0.3 =
* Shorten Darksky API field hint
* Fix demo shortcode syntax
* Use latest version of wpdtrt-exif and load as a test dependency
* Remove debugging code
* Check for array key rather than key value
* Update old release notes
* Use gulp bump to update version numbers from package.json
* Update wpdtrt-plugin to 1.4.5

= 0.0.2 =
* Migrate image quality settings from wpdtrt
* Migrate common wpdtrt-gallery styles into wpdtrt-plugin
* Migrate Bower & NPM to Yarn
* Update Node from 6.11.2 to 8.11.1
* Add messages required by shortcode demo
* Add SCSS partials for project-specific extends and variables
* Change tag badge to release badge
* Fix default .pot file
* Update wpdtrt-plugin to 1.3.1

= 0.0.1 =
* Initial version

== Upgrade Notice ==

= 0.0.1 =
* Initial release
