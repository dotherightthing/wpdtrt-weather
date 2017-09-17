<?php
/**
 * CSS imports
 *
 * This file contains PHP.
 *
 * @link        https://github.com/dotherightthing/wpdtrt-weather
 * @since       0.1.0
 *
 * @package     WPDTRT_Weather
 * @subpackage  WPDTRT_Weather/app
 */

if ( !function_exists( 'wpdtrt_weather_css_backend' ) ) {

  /**
   * Attach CSS for Settings > DTRT Weather
   *
   * @since       0.1.0
   */
  function wpdtrt_weather_css_backend() {

     $media = 'all';

    wp_enqueue_style( 'wpdtrt_weather_css_backend',
      WPDTRT_WEATHER_URL . 'css/wpdtrt-weather-admin.css',
      array(),
      WPDTRT_WEATHER_VERSION,
      $media
    );
  }

  add_action( 'admin_head', 'wpdtrt_weather_css_backend' );

}

if ( !function_exists( 'wpdtrt_weather_css_frontend' ) ) {

  /**
   * Attach CSS for front-end widgets and shortcodes
   *
   * @since       0.1.0
   */
  function wpdtrt_weather_css_frontend() {

    $media = 'all';

    /*
    wp_register_style( 'a_dependency',
      WPDTRT_WEATHER_URL . 'vendor/bower_components/a_dependency/a_dependency.css',
      array(),
      DEPENDENCY_VERSION,
      $media
    );
    */

    wp_enqueue_style( 'wpdtrt_weather',
      WPDTRT_WEATHER_URL . 'css/wpdtrt-weather.css',
      array(
        // load these registered dependencies first:
        'a_dependency'
      ),
      WPDTRT_WEATHER_VERSION,
      $media
    );

  }

  add_action( 'wp_enqueue_scripts', 'wpdtrt_weather_css_frontend' );

}

?>
