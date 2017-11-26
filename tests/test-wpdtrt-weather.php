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
 * Note: only function names prepended with test_ are run
 */
class WeatherTest extends WP_UnitTestCase {

	// STUBS

	/**
	 * Create a test page and attach a test attachment, which contains GPS data
	 *
	 * @return object $post
	 *
	 * @see https://pippinsplugins.com/unit-tests-for-wordpress-plugins-the-factory/
	 * @see https://core.trac.wordpress.org/browser/trunk/tests/phpunit/includes/factory/class-wp-unittest-factory-for-post.php
	 * @see https://core.trac.wordpress.org/browser/trunk/tests/phpunit/includes/factory/class-wp-unittest-factory-for-attachment.php
	 */
	function stub_post_with_featured_image() {
		$file = WPDTRT_WEATHER_PATH . 'tests/data/23465055912_ce8ff02e9f_o_Saihan_Tal.jpg';
		$post_id = $this->factory->post->create();
		$post = get_post( $post_id );
		$attachment_id = $this->factory->attachment->create_upload_object( $file, $post_id );
		set_post_thumbnail( $post_id, $attachment_id );

		return $post;
	}

	/**
	 * Set the value of the darksky_api_key
	 * this is usually entered into the textfield
	 * on the plugin settings page
	 */
	function stub_api_key() {
		global $wpdtrt_weather_plugin;

		$plugin_options = $wpdtrt_weather_plugin->get_plugin_options();
		$plugin_options['darksky_api_key']['value'] = '9a3c7a293c3a78a4169c1b71878b0a97';
		$wpdtrt_weather_plugin->set_plugin_options( $plugin_options );
	}

	// TESTS

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
		global $debug, $wpdtrt_weather_plugin;
		//global $post; // doesn't work to pass stubbed post to test_get_api_data

		/**
		 * Require the plugin dependency, so we can run its functions directly.
		 * @todo https://github.com/dotherightthing/wpdtrt-weather/issues/4
		 */
		$local_websites_directory = dirname( WPDTRT_WEATHER_PATH );
		require_once( $local_websites_directory . '/wpdtrt-exif/app/wpdtrt-exif-conversion.php');
		require_once( $local_websites_directory . '/wpdtrt-exif/app/wpdtrt-exif-data.php');

		$post = $this->stub_post_with_featured_image(); // post:3, attachment:4
		$data = $wpdtrt_weather_plugin->get_featured_image_latlng( $post );
		//$debug->log( $post->ID ); // 3

	    $this->assertTrue( function_exists('wpdtrt_exif_get_attachment_metadata_gps') );
	    $this->assertTrue( is_array( $data ) );
	    $this->assertArrayHasKey( 'latitude', $data );
	    $this->assertArrayHasKey( 'longitude', $data );
	}

	function test_get_api_data() {
		global $debug, $wpdtrt_weather_plugin;
		//global $post; // doesn't work to pass stubbed post from test_get_featured_image_latlng

		$this->stub_api_key();
		$post = $this->stub_post_with_featured_image(); // post:5, attachment:6
		$data = $wpdtrt_weather_plugin->get_api_data( $post );
	}
}
