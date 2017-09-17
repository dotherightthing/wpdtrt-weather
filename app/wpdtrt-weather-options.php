<?php
/**
 * Functionality for the WP Admin Plugin Options page
 *    WP Admin > Settings > DTRT Weather
 *
 * This file contains PHP.
 *
 * @link        https://github.com/dotherightthing/wpdtrt-weather
 * @since       0.1.0
 *
 * @package     WPDTRT_Weather
 * @subpackage  WPDTRT_Weather/app
 */

if ( !function_exists( 'wpdtrt_weather_menu' ) ) {


  /**
   * Display a link to the options page in the admin menu
   *
   * @since       0.1.0
   * @uses        ../../../../wp-admin/includes/plugin.php
   * @see         https://developer.wordpress.org/reference/functions/add_options_page/
   */
  function wpdtrt_weather_menu() {

    add_options_page(
      'DTRT Weather',
      'DTRT Weather',
      'manage_options',
      'wpdtrt-weather',
      'wpdtrt_weather_options_page'
    );
  }

  add_action('admin_menu', 'wpdtrt_weather_menu');

}

/**
 * Create the plugin options page
 */
if ( !function_exists( 'wpdtrt_weather_options_page' ) ) {

  /**
   * Render the appropriate UI on Settings > DTRT Weather
   *
   *    1. Take the user's selection (from the form input)
   *    2. Request the data from the API
   *    3. Store data in the options table:
   *       a. The user's selection: 'wpdtrt_weather_api_key',
   *    4. Render the options page
   *
   *    Note: Shortcode/widget options are specific to each instance of the shortcode/widget
   *    and are thus stored with those individual instances.
   *
   * @since       0.1.0
   */
  function wpdtrt_weather_options_page() {

    if ( ! current_user_can( 'manage_options' ) ) {
      wp_die( 'Sorry, you do not have sufficient permissions to access this page.' );
    }

    /**
     * Make this global available within the required statement
     */
    global $wpdtrt_weather_options;

    if ( isset( $_POST['wpdtrt_weather_form_submitted'] ) ) {

      // check that the form submission was legitimate
      $hidden_field = esc_html( $_POST['wpdtrt_weather_form_submitted'] );

      if ( $hidden_field === 'Y' ) {

        // 1. get user preferences from form submission
        $wpdtrt_weather_api_key = esc_html( $_POST['wpdtrt_weather_api_key'] );

        // 2. store user preferences in options object
        $wpdtrt_weather_options['wpdtrt_weather_api_key'] = $wpdtrt_weather_api_key;

        /**
         * Save options object to database
         *
         * Update the plugin data stored in the WP Options table
         * This function may be used in place of add_option, although it is not as flexible.
         * update_option will check to see if the option already exists.
         * If it does not, it will be added with add_option('option_name', 'option_value').
         * Unless you need to specify the optional arguments of add_option(),
         * update_option() is a useful catch-all for both adding and updating options.
         * @example update_option( string $option, mixed $value, string|bool $autoload = null )
         * @see https://codex.wordpress.org/Function_Reference/update_option
         */
        update_option( 'wpdtrt_weather', $wpdtrt_weather_options, null );
      }
    }

    /**
     * Load the plugin data stored in the WP Options table
     * Retrieves an option value based on an option name.
     * @example get_option( string $option, mixed $default = false )
     */
    $wpdtrt_weather_options = get_option( 'wpdtrt_weather' );

    // if options have been stored, recover them
    if ( $wpdtrt_weather_options !== '' ) {
      $wpdtrt_weather_api_key = $wpdtrt_weather_options['wpdtrt_weather_api_key'];
    }

    /**
     * 4. Load the HTML template
     * This function's variables will be available to this template.
     * @todo display the last generated timestamp on the options page
     */
    require_once(WPDTRT_WEATHER_PATH . 'templates/wpdtrt-weather-options.php');
  }

}

?>
