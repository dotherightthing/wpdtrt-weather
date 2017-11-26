<?php
/**
 * Unit tests, using PHPUnit and wp-cli.
 *
 * @package wpdtrt_weather
 * @see https://developer.wordpress.org/cli/commands/
 * @see https://core.trac.wordpress.org/browser/trunk/tests/phpunit/includes/factory.php
 * @see https://pippinsplugins.com/unit-tests-wordpress-plugins-writing-tests/
 * @see http://kb.dotherightthing.dan/php/wordpress/php-unit-testing-revisited/ for setup
 */

/**
 * WeatherTest unit tests, using PHPUnit and wp-cli.
 * Note that the plugin is 'active' within a WP test environment
 * so the plugin class has already been instantiated
 * with the options set in wpdtrt-weather.php
 */
class WeatherTest extends WP_UnitTestCase {

	/**
	 * Test get_plugin_options()
	 * Tests that retrieved plugin options match what was entered
	 */
	function test_get_plugin_options() {

		// note that $debug logs are output with the test output in Terminal :)
		//$this->setup(); // not required as the plugin has initialised already
		global $debug, $wpdtrt_weather_plugin;

		$data = $wpdtrt_weather_plugin->get_plugin_options();
		//$debug->log( $data );

	    $this->assertTrue( is_array( $data ) );
	    $this->assertArrayHasKey( 'darksky_api_key', $data );
	    $this->assertTrue( is_array( $data['darksky_api_key'] ) );

	    $this->assertEquals( 'password', $data['darksky_api_key']['type'] );
	    $this->assertEquals( 'Darksky API key', $data['darksky_api_key']['label'] );
	    $this->assertEquals( 'Register for a key at https://darksky.net/dev/register', $data['darksky_api_key']['tip'] );
	    // value is not set until the user enters data into settings
	    // we stub a value in later tests
	    $this->assertTrue( !isset( $data['darksky_api_key']['value'] ) );
	}

	function test_get_featured_image_latlng() {
		global $debug, $wpdtrt_weather_plugin, $post;

		/**
		 * Create a test page and attach a test attachment, which contains GPS data
		 * @see https://pippinsplugins.com/unit-tests-for-wordpress-plugins-the-factory/
		 * @see https://core.trac.wordpress.org/browser/trunk/tests/phpunit/includes/factory/class-wp-unittest-factory-for-attachment.php
		 */
		$file = WPDTRT_WEATHER_PATH . 'tests/data/23465055912_ce8ff02e9f_o_Saihan_Tal.jpg';
		$parent_id = 12345; // parent post ID
		$post = $this->factory->post->create( array(
			'ID' => $parent_id
		));
		$attachment_id = $this->factory->attachment->create_upload_object( $file, $parent_id );

		/**
		 * Require the plugin dependency, so we can run its functions directly.
		 * @todo https://github.com/dotherightthing/wpdtrt-weather/issues/4
		 */
		$local_websites_directory = dirname( WPDTRT_WEATHER_PATH );
		require_once( $local_websites_directory . '/wpdtrt-exif/app/wpdtrt-exif-data.php');

		$data = $wpdtrt_weather_plugin->get_featured_image_latlng();
		//$debug->log( $data );

	    $this->assertTrue( function_exists('wpdtrt_exif_get_attachment_metadata_gps') );
	    $this->assertTrue( is_array( $data ) );
	    $this->assertArrayHasKey( 'latitude', $data );
	    $this->assertArrayHasKey( 'longitude', $data );

	}

	function test_get_api_data() {
		global $debug, $wpdtrt_weather_plugin;

		// set the value of the darksky_api_key
		// this is usually entered into the textfield
		// on the plugin settings page
		$plugin_options = $wpdtrt_weather_plugin->get_plugin_options();
		$plugin_options['darksky_api_key']['value'] = '9a3c7a293c3a78a4169c1b71878b0a97';
		$wpdtrt_weather_plugin->set_plugin_options( $plugin_options );

		$data = $wpdtrt_weather_plugin->get_api_data();
		//$debug->log( $data );

		$this->assertTrue( true );
	}
}
