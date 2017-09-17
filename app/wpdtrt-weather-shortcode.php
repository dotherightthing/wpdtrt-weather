<?php
/**
 * Generate a shortcode, to embed the widget inside a content area.
 *
 * This file contains PHP.
 *
 * @link        https://github.com/dotherightthing/wpdtrt-weather
 * @link        https://generatewp.com/shortcodes/
 * @since       0.1.0
 *
 * @example     [wpdtrt_weather number="4" enlargement="yes"]
 * @example     do_shortcode( '[wpdtrt_weather number="4" enlargement="yes"]' );
 *
 * @package     WPDTRT_Weather
 * @subpackage  WPDTRT_Weather/app
 */

if ( !function_exists( 'wpdtrt_weather_shortcode' ) ) {

  /**
   * @param       array $atts
   *    Optional shortcode attributes specified by the user
   * @param       string $content
   *    Content within the enclosing shortcode tags
   *
   * @since       0.1.0
   * @uses        ../../../../wp-includes/shortcodes.php
   * @see         https://codex.wordpress.org/Function_Reference/add_shortcode
   * @see         https://codex.wordpress.org/Shortcode_API#Enclosing_vs_self-closing_shortcodes
   * @see         http://php.net/manual/en/function.ob-start.php
   * @see         http://php.net/manual/en/function.ob-get-clean.php
   */
  function wpdtrt_weather_shortcode( $atts, $content = null ) {

    // post object to get info about the post in which the shortcode appears
    global $post;

    // predeclare variables
    $before_widget = null;
    $before_title = null;
    $title = null;
    $after_title = null;
    $after_widget = null;
    $element = null;
    $shortcode = 'wpdtrt_weather';

    /**
     * Combine user attributes with known attributes and fill in defaults when needed.
     * @see https://developer.wordpress.org/reference/functions/shortcode_atts/
     */
    $atts = shortcode_atts(
      array(
        'element' => 'dd'
      ),
      $atts,
      $shortcode
    );

    // only overwrite predeclared variables
    extract( $atts, EXTR_IF_EXISTS );

    //$forecast = get_post_meta( $post->ID, 'wpdtrt_weather_forecast' );
    //if ( ! isset($forecast) || empty($forecast) ) {

      $wpdtrt_weather_options = get_option('wpdtrt_weather');
      $wpdtrt_weather_api_key = $wpdtrt_weather_options['wpdtrt_weather_api_key'];
      $forecast = wpdtrt_weather_get_data( $wpdtrt_weather_api_key );
      update_post_meta( $post->ID, 'wpdtrt_weather_forecast', $forecast );

    //}


    $min = 0;
    $max = 0;
    $icon = '';
    $summary = '';
    $unit = '';

    //wpdtrt_log('https://api.darksky.net/forecast/' . $args['api_key'] . '/' . $args['latitude'] . ',' . $args['longitude'] . ',' . $args['time']);

    // Get the day's historical forecast data
    $day = isset( $forecast->daily['data'] ) ? $forecast->daily['data'][0] : false;

    if ( $day ) {
      $min = isset( $day['temperatureMin'] ) ? intval( $day['temperatureMin'] ) : null;
      $max = isset( $day['temperatureMax'] ) ? intval( $day['temperatureMax'] ) : null;
      $icon = isset( $day['icon'] ) ? $forecast->get_icon( esc_attr( $day['icon'] ) ) : null; // get_entry_stats_weather($post_id)[0];
      $summary =  isset( $day['summary'] ) ? esc_attr( $day['summary'] ) : null; // get_entry_stats_weather($post_id)[1];
      $unit = '&deg;<abbr title="Centigrade">C</abbr>';
    }

    /**
     * ob_start — Turn on output buffering
     * This stores the HTML template in the buffer
     * so that it can be output into the content
     * rather than at the top of the page.
     */
    ob_start();

    require(WPDTRT_WEATHER_PATH . 'templates/wpdtrt-weather-front-end.php');

    /**
     * ob_get_clean — Get current buffer contents and delete current output buffer
     */
    $content = ob_get_clean();

    return $content;
  }

  /**
   * @param string $tag
   *    Shortcode tag to be searched in post content.
   * @param callable $func
   *    Hook to run when shortcode is found.
   */
  add_shortcode( 'wpdtrt_weather', 'wpdtrt_weather_shortcode' );

}

?>
