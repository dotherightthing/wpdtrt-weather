<?php
/**
 * DTRT Weather
 *
 * @package     WPDTRT_Weather
 * @author      Dan Smith
 * @copyright   2018 Do The Right Thing
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name:  DTRT Weather
 * Plugin URI:   https://github.com/dotherightthing/wpdtrt-weather
 * Description:  Displays historical weather information for the GPS location determined by the Featured Image.
 * Version:      0.3.4
 * Author:       Dan Smith
 * Author URI:   https://profiles.wordpress.org/dotherightthingnz
 * License:      GPLv2 or later
 * License URI:  http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  wpdtrt-weather
 * Domain Path:  /languages
 */

/**
 * Constants
 * WordPress makes use of the following constants when determining the path to the content and plugin directories.
 * These should not be used directly by plugins or themes, but are listed here for completeness.
 * WP_CONTENT_DIR  // no trailing slash, full paths only
 * WP_CONTENT_URL  // full url
 * WP_PLUGIN_DIR  // full path, no trailing slash
 * WP_PLUGIN_URL  // full url, no trailing slash
 *
 * WordPress provides several functions for easily determining where a given file or directory lives.
 * Always use these functions in your plugins instead of hard-coding references to the wp-content directory
 * or using the WordPress internal constants.
 * plugins_url()
 * plugin_dir_url()
 * plugin_dir_path()
 * plugin_basename()
 *
 * @see https://codex.wordpress.org/Determining_Plugin_and_Content_Directories#Constants
 * @see https://codex.wordpress.org/Determining_Plugin_and_Content_Directories#Plugins
 */

if ( ! defined( 'WPDTRT_WEATHER_VERSION' ) ) {
	/**
	 * Plugin version.
	 *
	 * WP provides get_plugin_data(), but it only works within WP Admin,
	 * so we define a constant instead.
	 *
	 * @see $plugin_data = get_plugin_data( __FILE__ ); $plugin_version = $plugin_data['Version'];
	 * @see https://wordpress.stackexchange.com/questions/18268/i-want-to-get-a-plugin-version-number-dynamically
	 */
	define( 'WPDTRT_WEATHER_VERSION', '0.3.4' );
}

if ( ! defined( 'WPDTRT_WEATHER_PATH' ) ) {
	/**
	 * Plugin directory filesystem path.
	 *
	 * @param string $file
	 * @return The filesystem directory path (with trailing slash)
	 * @see https://developer.wordpress.org/reference/functions/plugin_dir_path/
	 * @see https://developer.wordpress.org/plugins/the-basics/best-practices/#prefix-everything
	 */
	define( 'WPDTRT_WEATHER_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'WPDTRT_WEATHER_URL' ) ) {
	/**
	 * Plugin directory URL path.
	 *
	 * @param string $file
	 * @return The URL (with trailing slash)
	 * @see https://codex.wordpress.org/Function_Reference/plugin_dir_url
	 * @see https://developer.wordpress.org/plugins/the-basics/best-practices/#prefix-everything
	 */
	define( 'WPDTRT_WEATHER_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * ===== Dependencies =====
 */

/**
 * Determine the correct path to the PSR-4 autoloader.
 *
 * @see https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/issues/51
 */
if ( ! defined( 'WPDTRT_PLUGIN_CHILD' ) ) {
	define( 'WPDTRT_PLUGIN_CHILD', true );
}

/**
 * Determine the correct path to the PSR-4 autoloader.
 *
 * @see https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/issues/104
 * @see https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/wiki/Options:-Adding-WordPress-plugin-dependencies
 */
if ( defined( 'WPDTRT_WEATHER_TEST_DEPENDENCY' ) ) {
	$project_root_path = realpath( __DIR__ . '/../../..' ) . '/';
} else {
	$project_root_path = '';
}

require_once $project_root_path . 'vendor/autoload.php';

if ( is_admin() ) {
	// This replaces the TGMPA autoloader
	// @see dotherightthing/generator-wpdtrt-plugin-boilerplate#77
	// @see dotherightthing/wpdtrt-plugin-boilerplate#136.
	require_once $project_root_path . 'vendor/tgmpa/tgm-plugin-activation/class-tgm-plugin-activation.php';
}

// classes without composer.json files are loaded via Yarn.
require_once WPDTRT_WEATHER_PATH . 'vendor/joshuadavidnelson/wp-darksky/wp-darksky.php';
require_once WPDTRT_WEATHER_PATH . 'vendor/joshuadavidnelson/wp-darksky-weather-icon-forecast/wp-darksky-weather-icon-forecast.php';

// sub classes, not loaded via PSR-4.
// remove the includes you don't need, edit the files you do need.
require_once WPDTRT_WEATHER_PATH . 'src/class-wpdtrt-weather-plugin.php';
require_once WPDTRT_WEATHER_PATH . 'src/class-wpdtrt-weather-shortcode.php';
require_once WPDTRT_WEATHER_PATH . 'src/class-wpdtrt-weather-widget.php';

// log & trace helpers.
global $debug;
$debug = new DoTheRightThing\WPDebug\Debug();

/**
 * ===== WordPress Integration =====
 *
 * Comment out the actions you don't need.
 *
 * Notes:
 *  Default priority is 10. A higher priority runs later.
 *  register_activation_hook() is run before any of the provided hooks.
 *
 * @see https://developer.wordpress.org/plugins/hooks/actions/#priority
 * @see https://codex.wordpress.org/Function_Reference/register_activation_hook.
 */
register_activation_hook( dirname( __FILE__ ), 'wpdtrt_weather_helper_activate' );

add_action( 'init', 'wpdtrt_weather_plugin_init', 0 );
add_action( 'init', 'wpdtrt_weather_shortcode_init', 100 );
// add_action( 'widgets_init', 'wpdtrt_weather_widget_init', 10 ); // see dotherightthing/wpdtrt-plugin-boilerplate#183.

register_deactivation_hook( dirname( __FILE__ ), 'wpdtrt_weather_helper_deactivate' );

/**
 * ===== Plugin config =====
 */

/**
 * Register functions to be run when the plugin is activated.
 *
 * @see https://codex.wordpress.org/Function_Reference/register_activation_hook
 * @todo https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/issues/128
 * @see See also Plugin::helper_flush_rewrite_rules()
 */
function wpdtrt_weather_helper_activate() {
	flush_rewrite_rules();
}

/**
 * Register functions to be run when the plugin is deactivated.
 * (WordPress 2.0+)
 *
 * @see https://codex.wordpress.org/Function_Reference/register_deactivation_hook
 * @todo https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/issues/128
 * @see See also Plugin::helper_flush_rewrite_rules()
 */
function wpdtrt_weather_helper_deactivate() {
	flush_rewrite_rules();
}

/**
 * Plugin initialisaton
 *
 * We call init before widget_init so that the plugin object properties are available to it.
 * If widget_init is not working when called via init with priority 1, try changing the priority of init to 0.
 * init: Typically used by plugins to initialize. The current user is already authenticated by this time.
 * widgets_init: Used to register sidebars. Fired at 'init' priority 1 (and so before 'init' actions with priority ≥ 1!)
 *
 * @see https://wp-mix.com/wordpress-widget_init-not-working/
 * @see https://codex.wordpress.org/Plugin_API/Action_Reference
 * @todo Add a constructor function to WPDTRT_Weather_Plugin, to explain the options array
 */
function wpdtrt_weather_plugin_init() {
	// pass object reference between classes via global
	// because the object does not exist until the WordPress init action has fired.
	global $wpdtrt_weather_plugin;

	/**
	 * Global options
	 *
	 * @see https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/wiki/Options:-Adding-global-options Options: Adding global options
	 */
	$plugin_options = array(
		'darksky_api_key' => array(
			'type'  => 'password',
			'label' => __( 'Darksky API key', 'wpdtrt-weather' ),
			'size'  => 40,
			'tip'   => __( 'https://darksky.net/dev/register', 'wpdtrt-weather' ),
		),
	);

	/**
	 * Shortcode or Widget options
	 *
	 * @see https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/wiki/Options:-Adding-shortcode-or-widget-options Options: Adding shortcode or widget options
	 */
	$instance_options = array();

	/**
	 *  UI Messages
	 */
	$ui_messages = array(
		'demo_data_description'       => __( 'This demo was generated from the following data', 'wpdtrt-weather' ),
		'demo_data_displayed_length'  => __( 'results displayed', 'wpdtrt-weather' ),
		'demo_data_length'            => __( 'results', 'wpdtrt-weather' ),
		'demo_data_title'             => __( 'Demo data', 'wpdtrt-weather' ),
		'demo_date_last_updated'      => __( 'Data last updated', 'wpdtrt-weather' ),
		'demo_sample_title'           => __( 'Demo sample', 'wpdtrt-weather' ),
		'demo_shortcode_title'        => __( 'Demo shortcode', 'wpdtrt-weather' ),
		'insufficient_permissions'    => __( 'Sorry, you do not have sufficient permissions to access this page.', 'wpdtrt-weather' ),
		'no_options_form_description' => __( 'There aren\'t currently any options.', 'wpdtrt-weather' ),
		'noscript_warning'            => __( 'Please enable JavaScript', 'wpdtrt-weather' ),
		'options_form_description'    => __( 'Please enter your preferences.', 'wpdtrt-weather' ),
		'options_form_submit'         => __( 'Save Changes', 'wpdtrt-weather' ),
		'options_form_title'          => __( 'General Settings', 'wpdtrt-weather' ),
		'loading'                     => __( 'Loading latest data...', 'wpdtrt-weather' ),
		'success'                     => __( 'settings successfully updated', 'wpdtrt-weather' ),
	);

	/**
	 * Demo shortcode
	 *
	 * @see https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/wiki/Settings-page:-Adding-a-demo-shortcode Settings page: Adding a demo shortcode
	 */
	$demo_shortcode_params = array(
		'name'    => 'wpdtrt_weather_shortcode',
		'number'  => 1,
		'element' => 'p',
	);

	/**
	 * Plugin configuration
	 */
	$wpdtrt_weather_plugin = new WPDTRT_Weather_Plugin(
		array(
			'path'                  => WPDTRT_WEATHER_PATH,
			'url'                   => WPDTRT_WEATHER_URL,
			'version'               => WPDTRT_WEATHER_VERSION,
			'prefix'                => 'wpdtrt_weather',
			'slug'                  => 'wpdtrt-weather',
			'menu_title'            => __( 'Weather', 'wpdtrt-weather' ),
			'settings_title'        => __( 'Settings', 'wpdtrt-weather' ),
			'developer_prefix'      => 'DTRT',
			'messages'              => $ui_messages,
			'plugin_options'        => $plugin_options,
			'instance_options'      => $instance_options,
			'demo_shortcode_params' => $demo_shortcode_params,
		)
	);
}

/**
 * ===== Rewrite config =====
 */

/**
 * Register Rewrite
 */
function wpdtrt_weather_rewrite_init() {

	global $wpdtrt_weather_plugin;

	$wpdtrt_weather_rewrite = new WPDTRT_Weather_Rewrite(
		array()
	);
}

/**
 * ===== Shortcode config =====
 */

/**
 * Register Shortcode
 */
function wpdtrt_weather_shortcode_init() {

	global $wpdtrt_weather_plugin;

	$wpdtrt_weather_shortcode = new WPDTRT_Weather_Shortcode(
		array(
			'name'                      => 'wpdtrt_weather_shortcode',
			'plugin'                    => $wpdtrt_weather_plugin,
			'template'                  => 'weather',
			'selected_instance_options' => array(),
		)
	);
}

/**
 * ===== Taxonomy config =====
 */

/**
 * Register Taxonomy
 *
 * @return object Taxonomy/
 */
function wpdtrt_weather_taxonomy_init() {

	global $wpdtrt_weather_plugin;

	$wpdtrt_weather_taxonomy = new WPDTRT_Weather_Taxonomy(
		array()
	);

	// return a reference for unit testing.
	return $wpdtrt_weather_taxonomy;
}

/**
 * ===== Widget config =====
 */

/**
 * Register a WordPress widget, passing in an instance of our custom widget class
 * The plugin does not require registration, but widgets and shortcodes do.
 * Note: widget_init fires before init, unless init has a priority of 0
 *
 * @uses        ../../../../wp-includes/widgets.php
 * @see         https://codex.wordpress.org/Function_Reference/register_widget#Example
 * @see         https://wp-mix.com/wordpress-widget_init-not-working/
 * @see         https://codex.wordpress.org/Plugin_API/Action_Reference
 * @uses        https://github.com/dotherightthing/wpdtrt/tree/master/library/sidebars.php
 * @todo        Add form field parameters to the options array
 * @todo        Investigate the 'classname' option
 */
function wpdtrt_weather_widget_init() {

	global $wpdtrt_weather_plugin;

	$wpdtrt_weather_widget = new WPDTRT_Weather_Widget(
		array(
			'name'                      => 'wpdtrt_weather_widget',
			'title'                     => __( 'DTRT Weather Widget', 'wpdtrt-weather' ),
			'description'               => __( 'Display historical weather information for the GPS location determined by the Featured Image.', 'wpdtrt-weather' ),
			'plugin'                    => $wpdtrt_weather_plugin,
			'template'                  => 'weather',
			'selected_instance_options' => array(),
		)
	);

	register_widget( $wpdtrt_weather_widget );
}
