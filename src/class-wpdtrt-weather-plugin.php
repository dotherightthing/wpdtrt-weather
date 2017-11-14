<?php
/**
 * Plugin sub class.
 *
 * @package     wpdtrt_weather
 * @since       1.0.0
 * @version 	1.0.0
 */

/**
 * Plugin sub class.
 *
 * Extends the base class to inherit boilerplate functionality.
 * Adds application-specific methods.
 *
 * @since       1.0.0
 * @version 	1.0.0
 */
class WPDTRT_Weather_Plugin extends DoTheRightThing\WPPlugin\Plugin {

    /**
     * Hook the plugin in to WordPress
     * This constructor automatically initialises the object's properties
     * when it is instantiated,
     * using new WPDTRT_Weather_Plugin
     *
     * @param     array $settings Plugin options
     *
     * @version   1.1.0
     * @since     1.0.0
     */
    function __construct( $settings ) {

    	// add any initialisation specific to wpdtrt-weather here

		// Instantiate the parent object
		parent::__construct( $settings );
    }

    /**
     * Get the latitude and longitude from a post's/page's featured image.
     * 	to obtain a historical forecast for this location.
     *
     * @since       0.1.0
     * @version     1.0.0
     * @return 		array ('latitude', 'longitude')
     *
     * @uses https://github.com/dotherightthing/wpdtrt-exif
     */
    public function get_featured_image_latlng() {
	    global $post;

	    if ( ! function_exists('wpdtrt_exif_get_attachment_metadata_gps') ) {
		    global $debug;
	    	$debug->log( 'wpdtrt_weather missing dependency, please install required plugins' );
	    	return;
	    }

	    $featured_image_id = get_post_thumbnail_id( $post->ID );
	    $attachment_metadata = wp_get_attachment_metadata( $featured_image_id, false ); // core meta
	    $attachment_metadata_gps = wpdtrt_exif_get_attachment_metadata_gps( $attachment_metadata, 'number' );

	    return array(
	    	'latitude' => $attachment_metadata_gps['latitude'],
	    	'longitude' => $attachment_metadata_gps['longitude'],
	    );
    }

    /**
     * Request weather data from the API.
     * This overrides the placeholder method in the parent class.
	 *
     * @since       0.1.0
     * @version     1.0.0
     * @return 		object $data A Darksky forecast object ($min, $max, $icon, $alt, $unit)
     *
   	 * @see 		https://stackoverflow.com/questions/3200984/where-can-i-find-historical-raw-weather-data
     * @uses        ../../../../wp-includes/http.php
	 * @uses 		https://darksky.net/dev/docs/time-machine
	 * @uses 		https://github.com/joshuadavidnelson/wp-darksky
	 * @uses 		https://gist.github.com/joshuadavidnelson/12e9915ad81d62a6991c
	 * @uses 		https://github.com/erikflowers/weather-icons
     */
    public function get_api_data() {

		$plugin_options = $this->get_plugin_options();

		// if required data is missing, exit
		if ( !isset( $plugin_options['darksky_api_key']['value'] ) ) {
			global $debug;
			$debug->log('darksky_api_key has no value', true, 'get_api_data()');
			return (object)[];
		}

		$darksky_api_key = $plugin_options['darksky_api_key']['value']; // value must be set in options array

		$featured_image_latlng = $this->get_featured_image_latlng();

		if ( !isset( $featured_image_latlng['latitude'] ) ) {
			global $debug;
			$debug->log('No GPS location available for this featured image', true, 'get_api_data');
		}

	    $args = array(
	      'api_key'       => $darksky_api_key,
	      'latitude'      => $featured_image_latlng['latitude'],
	      'longitude'     => $featured_image_latlng['longitude'],
	      'time'          => get_the_date('U'), // the date the post was written
	      'cache_enabled' => ( WP_DEBUG === true ) ? false : true,
	      'query'         => array(
	        'units'       => 'si', // metric - French Système International d'Unités
	        'exclude'     => 'flags'
	      )
	    );

	    //$debug->log('https://api.darksky.net/forecast/' . $args['api_key'] . '/' . $args['latitude'] . ',' . $args['longitude'] . ',' . $args['time']);
	    $data = new DarkSky\Weather_Icon_Forecast( $args ); // No Weather Station Source info included

		// Save the data and retrieval time
		$this->set_plugin_data( $data );
		$this->set_plugin_data_options( array(
			'last_updated' => time()
		) );

	    return $data;
    }

	/**
	* Get the forecast day from an API call
	*
	* @return 		mixed The day object | null
	*
	* @since       	0.1.0
	* @version     	1.0.0
	*/
    public function get_api_day() {
		$plugin_data = $this->get_plugin_data();
		$day = isset( $plugin_data->daily['data'] ) ? $plugin_data->daily['data'][0] : false;
    	return $day;
    }

	/**
	* Get the forecast minimum from an API call
	*
	* @return 		mixed Minimum temperature | null
	*
	* @since       	0.1.0
	* @version     	1.0.0
	*/
    public function get_api_day_min() {
    	$day = $this->get_api_day();
    	$min = null;

    	if ( $day ) {
    		$min = isset( $day['temperatureMin'] ) ? intval( $day['temperatureMin'] ) : null;
    	}

    	return $min;
    }

	/**
	* Get the forecast maximum from an API call
	*
	* @return 		mixed Maximum temperature | null
	*
	* @since       	0.1.0
	* @version     	1.0.0
	*/
    public function get_api_day_max() {
    	$day = $this->get_api_day();
    	$max = null;

    	if ( $day ) {
    		$max = isset( $day['temperatureMax'] ) ? intval( $day['temperatureMax'] ) : null;
    	}

    	return $max;
    }

	/**
	* Get the forecast summary from an API call
	*
	* @return 		mixed Summary | null
	*
	* @since       	0.1.0
	* @version     	1.0.0
	*/
    public function get_api_day_summary() {
    	// get_entry_stats_weather($post_id)[1];
    	$day = $this->get_api_day();
    	$summary = null;

    	if ( $day ) {
    		$summary = isset( $day['summary'] ) ? esc_attr( $day['summary'] ) : null;
    	}

    	return $summary;
    }

	/**
	* Get the forecast icon from an API call
	*
	* @return 		mixed Weather icon | null
	*
	* @since       	0.1.0
	* @version     	1.0.0
	*/
    public function get_api_day_icon() {
    	$day = $this->get_api_day();
    	$icon = null;

    	if ( $day ) {
    		$icon = isset( $day['icon'] ) ? esc_attr( $day['icon'] ) : null;
    	}

    	return $icon;
    }

	/**
	* Get the forecast temperature unit from an API call
	*
	* @return 		mixed Temperature unit | null
	*
	* @since       	0.1.0
	* @version     	1.0.0
	*/
    public function get_api_day_unit() {
    	$day = $this->get_api_day();
    	$unit = null;

    	if ( $day ) {
    		$unit = '&deg;<abbr title="Centigrade">C</abbr>';
    	}

    	return $unit;
    }

	/**
	 * Add custom rewrite rules
	 * WordPress allows theme and plugin developers to programmatically specify new, custom rewrite rules.
	 *
	 * @see http://clivern.com/how-to-add-custom-rewrite-rules-in-wordpress/
	 * @see https://www.pmg.com/blog/a-mostly-complete-guide-to-the-wordpress-rewrite-api/
	 * @see https://www.addedbytes.com/articles/for-beginners/url-rewriting-for-beginners/
	 * @see http://codex.wordpress.org/Rewrite_API
	 *
	 * @since       1.0.0
	 * @version     1.0.0
	 */
	public function set_rewrite_rules() {

	    global $wp_rewrite;

	    // Add rewrite rules in case another plugin flushes rules
	  	// add_action('init', [$this, 'set_rewrite_rules'] );
	    // ...
	}
}

?>