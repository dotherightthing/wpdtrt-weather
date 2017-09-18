<?php
/**
 * API requests
 *
 * This file contains PHP.
 *
 * @link        https://github.com/dotherightthing/wpdtrt-weather
 * @since       0.1.0
 *
 * @package     WPDTRT_Weather
 * @subpackage  WPDTRT_Weather/app
 */

if ( !function_exists( 'wpdtrt_weather_get_data' ) ) {

  /**
   * Request weather data from the API
   *
   * @param       string $wpdtrt_weather_api_key The user's API key
   * @return object $forecast ($min, $max, $icon, $alt, $unit)
   *
   * @see https://stackoverflow.com/questions/3200984/where-can-i-find-historical-raw-weather-data
   * @uses https://darksky.net/dev/docs/time-machine
   * @uses https://github.com/joshuadavidnelson/wp-darksky
   * @uses https://gist.github.com/joshuadavidnelson/12e9915ad81d62a6991c
   * @uses https://github.com/erikflowers/weather-icons
   *
   * @todo toggle cache_enabled off if debugging enabled
   * @since 0.1.0
 */
  function wpdtrt_weather_get_data( $wpdtrt_weather_api_key ) {

    global $post;

    $featured_image_id = get_post_thumbnail_id( $post->ID );

    $attachment_metadata = wp_get_attachment_metadata( $featured_image_id, false ); // core meta
    $attachment_metadata_gps = wpdtrt_exif_get_attachment_metadata_gps( $attachment_metadata, 'number' );

    // TODO: add units as a shortcode option
    // TODO: add Centigrade as a shortcode option or get more intelligently from results

    $args = array(
      'api_key'       => $wpdtrt_weather_api_key,
      'latitude'      => $attachment_metadata_gps['latitude'],
      'longitude'     => $attachment_metadata_gps['longitude'],
      'time'          => get_the_date('U'),
      'cache_enabled' => false,
      'query'         => array(
        'units'       => 'si', // metric - French Système International d'Unités
        'exclude'     => 'flags'
      )
    );

    //wpdtrt_log('https://api.darksky.net/forecast/' . $args['api_key'] . '/' . $args['latitude'] . ',' . $args['longitude'] . ',' . $args['time']);
    $forecast = new DarkSky\Weather_Icon_Forecast( $args ); // No Weather Station Source info included

    return $forecast;
  }

}


?>
