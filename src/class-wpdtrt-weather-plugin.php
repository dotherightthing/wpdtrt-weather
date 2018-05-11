<?php
/**
 * Plugin sub class.
 *
 * @package     wpdtrt_weather
 * @version 	0.0.1
 * @since       0.7.5
 */

/**
 * Plugin sub class.
 *
 * Extends the base class to inherit boilerplate functionality.
 * Adds application-specific methods.
 *
 * @version 	0.0.1
 * @since       0.7.5
 */
class WPDTRT_Weather_Plugin extends DoTheRightThing\WPPlugin\r_1_4_15\Plugin {

    /**
     * Hook the plugin in to WordPress
     * This constructor automatically initialises the object's properties
     * when it is instantiated,
     * using new WPDTRT_Weather_Plugin
     *
     * @param     array $settings Plugin options
     *
	 * @version 	0.0.1
     * @since       0.7.5
     */
    function __construct( $settings ) {

    	// add any initialisation specific to wpdtrt-weather here

		// Instantiate the parent object
		parent::__construct( $settings );
    }

    //// START WORDPRESS INTEGRATION \\\\

    /**
     * Initialise plugin options ONCE.
     *
     * @param array $default_options
     *
     * @version     0.0.1
     * @since       0.7.5
     */
    protected function wp_setup() {

    	parent::wp_setup();

		// add actions and filters here
    }

    //// END WORDPRESS INTEGRATION \\\\

    //// START SETTERS AND GETTERS \\\\

    /**
     * Get the latitude and longitude from a post's/page's featured image.
     *  to obtain a historical forecast for this location.
     *
     * @since       0.1.0
     * @version     1.0.0
     * @param       object $post
     * @return      array ('latitude', 'longitude')
     *
     * @uses https://github.com/dotherightthing/wpdtrt-exif
     */
    public function get_featured_image_latlng( $post ) {

        $lat_lng = array();

        if ( ! class_exists('WPDTRT_Exif_Plugin') ) {
            return $lat_lng;
        }
        else if ( ! method_exists('WPDTRT_Exif_Plugin', 'get_attachment_metadata_gps') ) {
            return $lat_lng;
        }
        else if ( !isset( $post ) ) {
            return $lat_lng;
        }

        global $wpdtrt_exif_plugin; // created by wpdtrt-exif.php

        $featured_image_id = get_post_thumbnail_id( $post->ID );

        $attachment_metadata = wp_get_attachment_metadata( $featured_image_id, false ); // core meta

        $attachment_metadata_gps = $wpdtrt_exif_plugin->get_attachment_metadata_gps( $attachment_metadata, 'number', $post );

        if ( ! isset( $attachment_metadata_gps['latitude'], $attachment_metadata_gps['longitude'] ) ) {
            return array();
        }

        $lat_lng = array(
            'latitude' => $attachment_metadata_gps['latitude'],
            'longitude' => $attachment_metadata_gps['longitude'],
        );

        return $lat_lng;
    }

    /**
     * Request weather data from the API.
     *  This overrides the method in the parent class.
     *
     * @since       0.1.0
     * @version     1.0.0
     * @param       object $test_post Optional $post object for unit testing
     * @return      object $data A Darksky forecast object ($min, $max, $icon, $alt, $unit)
     *
     * @see         https://stackoverflow.com/questions/3200984/where-can-i-find-historical-raw-weather-data
     * @uses        ../../../../wp-includes/http.php
     * @uses        https://darksky.net/dev/docs/time-machine
     * @uses        https://github.com/joshuadavidnelson/wp-darksky
     * @uses        https://gist.github.com/joshuadavidnelson/12e9915ad81d62a6991c
     * @uses        https://github.com/erikflowers/weather-icons
     */
    public function get_api_data( $test_post=null ) {

        global $post;

        if ( isset( $test_post ) ) {
            $post = $test_post;
        }

        $plugin_options = $this->get_plugin_options();

        // Weather_Icon_Forecast is an object containing arrays
        $data = (object)[];

        // if required data is missing, exit
        if ( key_exists('value', $plugin_options['darksky_api_key']) ) {
            $darksky_api_key = $plugin_options['darksky_api_key']['value'];
        }
        else {
            return $data;
        }

        // https://github.com/dotherightthing/wpdtrt-weather/issues/7
        $featured_image_latlng = $this->get_featured_image_latlng( $post );

        if ( !isset( $featured_image_latlng['latitude'] ) ) {
            return $data;
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

        // json_decode() expects parameter 1 to be string
        // $data = json_decode( $data, true );

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
     * @return       mixed The day object | null
     *
     * @since        0.1.0
     * @version      1.0.0
     */
    public function get_api_day() {
        $plugin_data = $this->get_plugin_data();
        $day = isset( $plugin_data->daily['data'] ) ? $plugin_data->daily['data'][0] : false;
        return $day;
    }

    /**
     * Get the forecast minimum from an API call
     *
     * @return       mixed Minimum temperature | null
     *
     * @since        0.1.0
     * @version      1.0.0
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
     * @return       mixed Maximum temperature | null
     *
     * @since        0.1.0
     * @version      1.0.0
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
     * @return       mixed Summary | null
     *
     * @since        0.1.0
     * @version      1.0.0
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
     * @return       mixed Weather icon | null
     *
     * @since        0.1.0
     * @version      1.0.0
     */
    public function get_api_day_icon() {
        $day = $this->get_api_day();
        $data = $this->get_plugin_data();
        $icon = null;

        if ( $day ) {
            $icon_data = isset( $day['icon'] ) ? esc_attr( $day['icon'] ) : null;

            if ( isset( $icon_data ) ) {
                // call DarkSky\Weather_Icon_Forecast methid get_icon()
                $icon = $data->get_icon( $icon_data );
            }
        }

        return $icon;
    }

    /**
     * Get the forecast temperature unit from an API call
     *
     * @return       mixed Temperature unit | null
     *
     * @since        0.1.0
     * @version      1.0.0
     */
    public function get_api_day_unit() {
        $day = $this->get_api_day();
        $unit = null;

        if ( $day ) {
            $unit = '&deg;<abbr title="Centigrade">C</abbr>';
        }

        return $unit;
    }

    //// END SETTERS AND GETTERS \\\\

    //// START RENDERERS \\\\
    //// END RENDERERS \\\\

    //// START FILTERS \\\\
    //// END FILTERS \\\\

    //// START HELPERS \\\\
    //// END HELPERS \\\\
}

?>