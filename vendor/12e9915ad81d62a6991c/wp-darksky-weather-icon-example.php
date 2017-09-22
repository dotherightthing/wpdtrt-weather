<?php
/**
 * A helper class for that extends the WP-DarkSky helper class with Weather Icons.
 * 
 * You'll need to add the Weather Icons stylesheet, web fonts, etc and enqueue them correctly.
 *
 * @see weathericons.io
 * @see https://developer.wordpress.org/reference/functions/wp_enqueue_style/
 * @see https://darksky.net/dev/docs/
 *
 * @link https://github.com/joshuadavidnelson/wp-darksky
 *
 * @since 1.0.0
 * @since 1.1.0 - Update for new Dark Sky API (versus old Forecast.io naming)
 *
 * @author Joshua David Nelson, josh@joshuadnelson.com
 */

$args = array(
	'api_key' 	=> $api_key, // set your api key
	'latitude'	=> $lat, // set your latitude
	'longitude'	=> $long, // set your longitude
	'query'		=> array( 'units' => 'us', 'exclude' => 'flags' )
);
$forecast = new DarkSky\Weather_Icon_Forecast( $args );

// Get the current forecast for a given latitude and longitude
$daily = isset( $forecast->daily['data'] ) ? $forecast->daily['data'] : '';

$current = '';
$title_attr = '';

if( is_array( $daily ) ) {
	$date_format = 'n/j/Y';
	$time_now = date( $date_format, current_time( 'timestamp' ) );
	foreach( $daily as $day ) {
		if( isset( $day['time'] ) && $time_now == date( $date_format, $day['time'] ) ) {
			// check the variables are setup
			if( isset( $day['temperatureMin'], $day['temperatureMax'] ) && !empty( $day['temperatureMin'] ) && !empty( $day['temperatureMax'] ) ) {
				
				// The temperature output
				$current = number_format( $day['temperatureMin'], 0 ) . ' / ' . number_format( $day['temperatureMax'], 0 );
				
				// Set the icon, if there is one
				$icon = !empty( $day['icon'] ) ? $forecast->get_icon( esc_attr( $day['icon'] ) ) : '';
				
				// set the title attribute to the forecast summary
				$title_attr = !empty( $day['summary'] ) ? ' title="' . esc_attr( $day['summary'] ) . '"' : '';
			}
		}
	}
}

// If we have a valid forecast, we can build the widget
if( ! empty( $current ) )
	echo '<div class="widget weather-widget"><h5 class="title" id="weather-widget-title">Today\'s Weather</h5>' . $icon . '<span class="temp">' . $current . ' &deg;</span></div>';