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

namespace DarkSky;

class Weather_Icon_Forecast extends Forecast {
	
	/**
	 * Weather Icons mapped for Forecast.io.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $icons = array(
		'clear-day' 		=> 'wi-forecast-io-clear-day',
		'clear-night' 		=> 'wi-forecast-io-clear-night',
		'rain'			=> 'wi-forecast-io-rain',
		'snow'			=> 'wi-forecast-io-snow',
		'sleet'			=> 'wi-forecast-io-sleet',
		'strong-wind'		=> 'wi-forecast-io-wind',
		'fog'			=> 'wi-forecast-io-fog',
		'cloudy'		=> 'wi-forecast-io-cloudy',
		'partly-cloudy-day'	=> 'wi-forecast-io-partly-cloudy-day',
		'partly-cloudy-night'	=> 'wi-forecast-io-partly-cloudy-night',
		'hail'			=> 'wi-forecast-io-hail',
		'thunderstorm'		=> 'wi-forecast-io-thunderstorm',
		'tornado'		=> 'wi-forecast-io-tornado',
	);
	
	/**
	 * Build it.
	 * 
	 * @uses wp_array_slice_assoc()
	 * @uses wp_parse_args()
	 * 
	 * @since 1.0.0
	 * 
	 * @param array $args The array of arguments.
	 */
	public function __construct( $args = array() ) {
		parent::__construct( $args );
	}
	
	/**
	 * Grab a weather icon.
	 * 
	 * @since 1.0.0
	 * 
	 * @param string $icon_name The Dark Sky icon name.
	 * 
	 * @return string $icon Icon print out or empty string. 
	 */
	public function get_icon( $icon_name ) {
		if( array_key_exists( esc_attr( $icon_name ), $this->icons ) ) {
			return '<i class="wi ' . $this->icons[ esc_attr( $icon_name ) ] . '"></i>';
		} else {
			return '';
		}
	}
}