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
     * Request weather data from the API.
     * This overrides the placeholder method in the parent class.
     *
     * @uses        ../../../../wp-includes/http.php
     * @see         https://developer.wordpress.org/reference/functions/wp_remote_get/
     * @see 		https://codex.wordpress.org/HTTP_API#Other_Arguments
   	 * @see https://stackoverflow.com/questions/3200984/where-can-i-find-historical-raw-weather-data
	 * @uses https://darksky.net/dev/docs/time-machine
	 * @uses https://github.com/joshuadavidnelson/wp-darksky
	 * @uses https://gist.github.com/joshuadavidnelson/12e9915ad81d62a6991c
	 * @uses https://github.com/erikflowers/weather-icons
	 *
     * @since       0.1.0
     * @version     1.0.0
     *
     * @return object $forecast ($min, $max, $icon, $alt, $unit)
	 * @todo toggle cache_enabled off if debugging enabled
     */
    public function get_api_data() {

	    global $debug;
	    global $post;

	    if ( ! function_exists('wpdtrt_exif_get_attachment_metadata_gps') ) {
	      	$debug->log( 'wpdtrt_weather missing dependency, please install required plugins' );
	      return;
	    }

		$plugin_options = $this->get_plugin_options();

		$darksky_api_key = $plugin_options['darksky_api_key']['value']; // value must be set in options array

	    $featured_image_id = get_post_thumbnail_id( $post->ID );

	    $attachment_metadata = wp_get_attachment_metadata( $featured_image_id, false ); // core meta
	    $attachment_metadata_gps = wpdtrt_exif_get_attachment_metadata_gps( $attachment_metadata, 'number' );

	    // TODO: add units as a shortcode option
	    // TODO: add Centigrade as a shortcode option or get more intelligently from results

	    $args = array(
	      'api_key'       => $darksky_api_key,
	      'latitude'      => $attachment_metadata_gps['latitude'],
	      'longitude'     => $attachment_metadata_gps['longitude'],
	      'time'          => get_the_date('U'),
	      'cache_enabled' => false,
	      'query'         => array(
	        'units'       => 'si', // metric - French Système International d'Unités
	        'exclude'     => 'flags'
	      )
	    );

	    //$debug->log('https://api.darksky.net/forecast/' . $args['api_key'] . '/' . $args['latitude'] . ',' . $args['longitude'] . ',' . $args['time']);
	    $forecast = new DarkSky\Weather_Icon_Forecast( $args ); // No Weather Station Source info included

	    return $forecast;
    }

	/**
	* Get the title of an API result item
	*
	* @param 	   	object Single API data object
	* @return 		string The title
	*
	* @since       	0.1.0
	* @version     	1.0.0
	* @todo 		Is this useful for weather?
	*/
    public function get_api_title( $object ) {

    	$title = '';

    	if ( isset( $object->{'title'} ) ) {
    		$title = $object->{'title'};
    	}

    	return $title;
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