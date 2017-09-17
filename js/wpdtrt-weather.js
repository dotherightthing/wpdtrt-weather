/**
 * Scripts for the public front-end
 *
 * This file contains JavaScript.
 *    PHP variables are provided in wpdtrt_weather_config.
 *
 * @link        https://github.com/dotherightthing/wpdtrt-weather
 * @since       0.1.0
 *
 * @package     WPDTRT_Weather
 * @subpackage  WPDTRT_Weather/js
 */

jQuery(document).ready(function($){

	$('.wpdtrt-weather-badge').hover(function() {
		$(this).find('.wpdtrt-weather-badge-info').stop(true, true).fadeIn(200);
	}, function() {
		$(this).find('.wpdtrt-weather-badge-info').stop(true, true).fadeOut(200);
	});

  $.post( wpdtrt_weather_config.ajax_url, {
    action: 'wpdtrt_weather_data_refresh'
  }, function( response ) {
    //console.log( 'Ajax complete' );
  });

});
