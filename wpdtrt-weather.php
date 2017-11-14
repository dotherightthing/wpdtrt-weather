<?php
/*
Plugin Name:  DTRT Weather
Plugin URI:   https://github.com/dotherightthing/wpdtrt-weather
Description:  Displays historical weather information for the GPS location determined by the Featured Image.
Version:      0.1.0
Author:       Dan Smith
Author URI:   https://profiles.wordpress.org/dotherightthingnz
License:      GPLv2 or later
License URI:  http://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  wpdtrt-weather
Domain Path:  /languages
*/

//require_once(WPDTRT_WEATHER_PATH . 'vendor/wp-darksky/wp-darksky.php');
//require_once(WPDTRT_WEATHER_PATH . 'vendor/12e9915ad81d62a6991c/wp-darksky-weather-icon-forecast.php');
require_once plugin_dir_path( __FILE__ ) . "vendor/autoload.php";

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
 * @link https://codex.wordpress.org/Determining_Plugin_and_Content_Directories#Constants
 * @link https://codex.wordpress.org/Determining_Plugin_and_Content_Directories#Plugins
 */

if( ! defined( 'WPDTRT_WEATHER_VERSION' ) ) {
/**
 * Plugin version.
 *
 * WP provides get_plugin_data(), but it only works within WP Admin,
 * so we define a constant instead.
 *
 * @example $plugin_data = get_plugin_data( __FILE__ ); $plugin_version = $plugin_data['Version'];
 * @link https://wordpress.stackexchange.com/questions/18268/i-want-to-get-a-plugin-version-number-dynamically
 *
 * @since     0.1.0
 * @version   0.1.0
 */
  define( 'WPDTRT_WEATHER_VERSION', '0.1.0' );
}

if( ! defined( 'WPDTRT_WEATHER_PATH' ) ) {
/**
 * Plugin directory filesystem path.
 *
 * @param string $file
 * @return The filesystem directory path (with trailing slash)
 *
 * @link https://developer.wordpress.org/reference/functions/plugin_dir_path/
 * @link https://developer.wordpress.org/plugins/the-basics/best-practices/#prefix-everything
 *
 * @since     0.1.0
 * @version   0.1.0
 */
  define( 'WPDTRT_WEATHER_PATH', plugin_dir_path( __FILE__ ) );
}

if( ! defined( 'WPDTRT_WEATHER_URL' ) ) {
/**
 * Plugin directory URL path.
 *
 * @param string $file
 * @return The URL (with trailing slash)
 *
 * @link https://codex.wordpress.org/Function_Reference/plugin_dir_url
 * @link https://developer.wordpress.org/plugins/the-basics/best-practices/#prefix-everything
 *
 * @since     0.1.0
 * @version   0.1.0
 */
  define( 'WPDTRT_WEATHER_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * Include plugin logic
 *
 * @since     0.1.0
 * @version   0.1.0
 */

  // base class
  // redundant, but includes the composer-generated autoload file if not already included
  require_once(WPDTRT_WEATHER_PATH . 'vendor/dotherightthing/wpdtrt-plugin/index.php');

  // sub classes
  require_once(WPDTRT_WEATHER_PATH . 'src/class-wpdtrt-weather-plugin.php');
  require_once(WPDTRT_WEATHER_PATH . 'src/class-wpdtrt-weather-widgets.php');

  // log & trace helpers
  $debug = new DoTheRightThing\WPDebug\Debug;

  /**
   * Plugin initialisaton
   *
   * We call init before widget_init so that the plugin object properties are available to it.
   * If widget_init is not working when called via init with priority 1, try changing the priority of init to 0.
   * init: Typically used by plugins to initialize. The current user is already authenticated by this time.
   * └─ widgets_init: Used to register sidebars. Fired at 'init' priority 1 (and so before 'init' actions with priority ≥ 1!)
   *
   * @see https://wp-mix.com/wordpress-widget_init-not-working/
   * @see https://codex.wordpress.org/Plugin_API/Action_Reference
   * @todo Add a constructor function to WPDTRT_Weather_Plugin, to explain the options array
   */
  function wpdtrt_weather_init() {
    // pass object reference between classes via global
    // because the object does not exist until the WordPress init action has fired
    global $wpdtrt_weather_plugin;

    /**
     * Admin settings
     */
    $plugin_options = array(
      'darksky_api_key' => array(
        'type' => 'password',
        'label' => __('Darksky API key', 'wpdtrt-weather'),
        'tip' => __('Register for a key at https://darksky.net/dev/register', 'wpdtrt-weather')
      )
    );

    /**
     * All options available to Widgets and Shortcodes
     */
    $instance_options = array(
      'element' => array(
        'type' => 'select',
        'label' => __('Element', 'wpdtrt-weather'),
        'options' => array(
          'dd' => array(
            'text' => __('Definition list item (dd)', 'wpdtrt-weather')
          ),
          'li' => array(
            'text' => __('List item (li)', 'wpdtrt-weather')
          ),
          'p' => array(
            'text' => __('Paragraph (p)', 'wpdtrt-weather')
          ),
          'span' => array(
            'text' => __('Span (span)', 'wpdtrt-weather')
          ),
        ),
        'tip' => __('Wrapping HTML element', 'wpdtrt-weather')
      )
    );

    $wpdtrt_weather_plugin = new WPDTRT_Weather_Plugin(
      array(
        'url' => WPDTRT_WEATHER_URL,
        'prefix' => 'wpdtrt_weather',
        'slug' => 'wpdtrt-weather',
        'menu_title' => __('Weather', 'wpdtrt-weather'),
        'developer_prefix' => 'DTRT',
        'path' => WPDTRT_WEATHER_PATH,
        'messages' => array(
          'loading' => __('Loading latest data...', 'wpdtrt-weather'),
          'success' => __('settings successfully updated', 'wpdtrt-weather'),
          'insufficient_permissions' => __('Sorry, you do not have sufficient permissions to access this page.', 'wpdtrt-weather'),
          'options_form_title' => __('General Settings', 'wpdtrt-weather'),
          'options_form_description' => __('Please enter your preferences', 'wpdtrt-weather'),
          'options_form_submit' => __('Save Changes', 'wpdtrt-weather')
        ),
        'plugin_options' => $plugin_options,
        'instance_options' => $instance_options,
        'version' => WPDTRT_WEATHER_VERSION,
        'plugin_dependencies' => array(
          array(
            'name'          => 'DTRT EXIF',
            'slug'          => 'wpdtrt-exif',
            'source'        => 'https://github.com/dotherightthing/wpdtrt-exif/archive/master.zip',
            'required'      => true,
            'is_callable'   => 'wpdtrt_exif_get_attachment_metadata_gps'
          )
        )
      )
    );
  }

  add_action( 'init', 'wpdtrt_weather_init', 0 );

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
   *
   * @since       0.1.0
   * @version     0.1.0
   * @todo        Add form field parameters to the options array
   * @todo        Investigate the 'classname' option
   */
  function wpdtrt_weather_widget_1_init() {

    global $wpdtrt_weather_plugin;

    $wpdtrt_weather_widget_1 = new WPDTRT_Weather_Widget_1(
      array(
        'name' => 'wpdtrt_weather_widget_1',
        'title' => __('DTRT Weather Widget', 'wpdtrt-weather'),
        'description' => __('Display historical weather information for the GPS location determined by the Featured Image.', 'wpdtrt-weather'),
        'plugin' => $wpdtrt_weather_plugin,
        'template' => 'weather',
        'selected_instance_options' => array(
          'element',
        )
      )
    );

    register_widget( $wpdtrt_weather_widget_1 );
  }

  add_action( 'widgets_init', 'wpdtrt_weather_widget_1_init' );

  /**
   * Register Shortcode
   *
   * @todo Add centigrade as a shortcode option (#1)
   * @todo Add units as a shortcode option (#2)
   */
  function wpdtrt_weather_shortcode_1_init() {

    global $wpdtrt_weather_plugin;

    $wpdtrt_weather_shortcode_1 = new DoTheRightThing\WPPlugin\Shortcode(
      array(
        'name' => 'wpdtrt_weather_shortcode_1',
        'plugin' => $wpdtrt_weather_plugin,
        'template' => 'weather',
        'selected_instance_options' => array(
          'element',
        )
      )
    );
  }

  add_action( 'init', 'wpdtrt_weather_shortcode_1_init', 100 );

  /**
   * Register functions to be run when the plugin is activated.
   *
   * @see https://codex.wordpress.org/Function_Reference/register_activation_hook
   *
   * @since     0.6.0
   * @version   0.1.0
   */
  function wpdtrt_weather_activate() {
    //wpdtrt_weather_rewrite_rules();
    flush_rewrite_rules();
  }

  register_activation_hook(__FILE__, 'wpdtrt_weather_activate');

  /**
   * Register functions to be run when the plugin is deactivated.
   *
   * (WordPress 2.0+)
   *
   * @see https://codex.wordpress.org/Function_Reference/register_deactivation_hook
   *
   * @since     0.6.0
   * @version   0.1.0
   */
  function wpdtrt_weather_deactivate() {
    flush_rewrite_rules();
  }

  register_deactivation_hook(__FILE__, 'wpdtrt_weather_deactivate');

?>
