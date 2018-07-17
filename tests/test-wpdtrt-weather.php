<?php
/**
 * Unit tests, using PHPUnit, wp-cli, WP_UnitTestCase
 *
 * The plugin is 'active' within a WP test environment
 * so the plugin class has already been instantiated
 * with the options set in wpdtrt-gallery.php
 *
 * Only function names prepended with test_ are run.
 * $debug logs are output with the test output in Terminal
 * A failed assertion may obscure other failed assertions in the same test.
 *
 * @package     WPDTRT_Weather
 * @version     0.0.1
 * @since       0.7.5
 *
 * @see http://kb.dotherightthing.dan/php/wordpress/php-unit-testing-revisited/ - Links
 * @see http://richardsweeney.com/testing-integrations/
 * @see https://gist.github.com/benlk/d1ac0240ec7c44abd393 - Collection of notes on WP_UnitTestCase
 * @see https://core.trac.wordpress.org/browser/trunk/tests/phpunit/includes/factory.php
 * @see https://core.trac.wordpress.org/browser/trunk/tests/phpunit/includes//factory/
 * @see https://stackoverflow.com/questions/35442512/how-to-use-wp-unittestcase-go-to-to-simulate-current-pageclass-wp-unittest-factory-for-term.php
 * @see https://codesymphony.co/writing-wordpress-plugin-unit-tests/#object-factories
 */

/**
 * WP_UnitTestCase unit tests for wpdtrt_weather
 */
class WPDTRT_WeatherTest extends WP_UnitTestCase {

	/**
	 * ===== Mock Objects =====
	 */

	/**
	 * Create a test page and attach a test attachment, which contains GPS data
	 *
	 * @param string $filename The image file name (added to debug missing metadata, but not helping).
	 * @return object $post
	 * @see https://pippinsplugins.com/unit-tests-for-wordpress-plugins-the-factory/
	 * @see https://core.trac.wordpress.org/browser/trunk/tests/phpunit/includes/factory/class-wp-unittest-factory-for-post.php
	 * @see https://core.trac.wordpress.org/browser/trunk/tests/phpunit/includes/factory/class-wp-unittest-factory-for-attachment.php
	 */
	public function mock_post_with_featured_image( $filename ) {
		$file          = WPDTRT_WEATHER_PATH . 'tests/data/' . $filename;
		$post_id       = $this->factory->post->create();
		$post          = get_post( $post_id );
		$attachment_id = $this->factory->attachment->create_upload_object( $file, $post_id );
		set_post_thumbnail( $post_id, $attachment_id );

		return $post;
	}

	/**
	 * Set the value of the darksky_api_key
	 * this is usually entered into the textfield
	 * on the plugin settings page
	 */
	public function mock_api_key() {
		global $wpdtrt_weather_plugin;

		$plugin_options                             = $wpdtrt_weather_plugin->get_plugin_options();
		$plugin_options['darksky_api_key']['value'] = '9a3c7a293c3a78a4169c1b71878b0a97';
		$wpdtrt_weather_plugin->set_plugin_options( $plugin_options );
	}

	/**
	 * ===== Assertions =====
	 */

	/**
	 * Compare two HTML fragments.
	 *
	 * @param string $expected Expected value.
	 * @param string $actual Actual value.
	 * @param string $error_message Message to show when strings don't match.
	 * @uses https://stackoverflow.com/a/26727310/6850747
	 */
	protected function assertEqualHtml( $expected, $actual, $error_message ) {
		$from = [ '/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s', '/> </s' ];
		$to   = [ '>', '<', '\\1', '><' ];
		$this->assertEquals(
			preg_replace( $from, $to, $expected ),
			preg_replace( $from, $to, $actual ),
			$error_message
		);
	}

	/**
	 * SetUp
	 * Automatically called by PHPUnit before each test method is run
	 */
	public function setUp() {
		// Make the factory objects available.
		parent::setUp();

		$this->post_id_1 = $this->create_post( array(
			'post_title'   => 'DTRT Weather test',
			'post_date'    => '2018-03-14 19:00:00',
			'post_content' => 'This is a simple test',
		));
	}

	/**
	 * TearDown
	 * Automatically called by PHPUnit after each test method is run
	 *
	 * @see https://codesymphony.co/writing-wordpress-plugin-unit-tests/#object-factories
	 */
	public function tearDown() {

		parent::tearDown();

		wp_delete_post( $this->post_id_1, true );
	}

	/**
	 * Create post
	 *
	 * @param array $options Post options (post_title, post_date, post_content).
	 * @return number $post_id
	 * @see https://developer.wordpress.org/reference/functions/wp_insert_post/
	 * @see https://wordpress.stackexchange.com/questions/37163/proper-formatting-of-post-date-for-wp-insert-post
	 * @see https://codex.wordpress.org/Function_Reference/wp_update_post
	 */
	public function create_post( $options ) {

		$post_title   = null;
		$post_date    = null;
		$post_content = null;

		extract( $options, EXTR_IF_EXISTS );

		$post_id = $this->factory->post->create([
			'post_title'   => $post_title,
			'post_date'    => $post_date,
			'post_content' => $post_content,
			'post_type'    => 'post',
			'post_status'  => 'publish',
		]);

		return $post_id;
	}

	/**
	 * ===== Tests =====
	 */

	/**
	 * Test get_api_data()
	 * Tests that API data is retrieved
	 *
	 * @todo this method calls get_featured_image_latlng() but fails to get latitude and longitude
	 * @todo add tests to wpdtrt-exif
	 * @todo add tests for functions which parse the api data
	 */
	public function __test_get_api_data() {
		global $debug, $wpdtrt_weather_plugin;
		// global $post; // doesn't work to pass mock post from test_get_featured_image_latlng.
		$this->mock_api_key();
		$post = $this->mock_post_with_featured_image( 'MDM_20151206_155919_20151206_1559_Outer_Mongolia.jpg' ); // post:5, attachment:6.
		$data = $wpdtrt_weather_plugin->get_api_data( $post );

		// cast data object to an array.
		$data_arr = (array) $data;

		// test that object is not empty.
		$this->assertTrue( ! empty( $data_arr ) );
	}

	/**
	 * Test get_plugin_options()
	 * Tests that retrieved plugin options match what was entered
	 */
	public function test_get_plugin_options() {

		// note that $debug logs are output with the test output in Terminal :)
		// $this->setup(); // not required as the plugin has initialised already.
		global $debug, $wpdtrt_weather_plugin;

		$data = $wpdtrt_weather_plugin->get_plugin_options();
		// $debug->log( $data );
		$this->assertTrue( is_array( $data ) );
		$this->assertArrayHasKey( 'darksky_api_key', $data );
		$this->assertTrue( is_array( $data['darksky_api_key'] ) );

		$this->assertEquals( 'password', $data['darksky_api_key']['type'] );
		$this->assertEquals( 'Darksky API key', $data['darksky_api_key']['label'] );
		$this->assertEquals( 'https://darksky.net/dev/register', $data['darksky_api_key']['tip'] );
		// value is not set until the user enters data into settings
		// we stub a value in later tests.
		$this->assertTrue( ! isset( $data['darksky_api_key']['value'] ) );
	}

	/**
	 * Test get_featured_image_latlng()
	 * Tests that latitude and longitude are returned
	 */
	public function __test_get_featured_image_latlng() {
		global $debug, $wpdtrt_weather_plugin;
		// global $post; // doesn't work to pass mock post to test_get_api_data.
		$post = $this->mock_post_with_featured_image( '23465055912_ce8ff02e9f_o_Saihan_Tal.jpg' ); // post:3, attachment:4.
		$data = $wpdtrt_weather_plugin->get_featured_image_latlng( $post );
		// $debug->log( $post->ID ); // 3.
		$this->assertTrue( function_exists( 'wpdtrt_exif_get_attachment_metadata_gps' ) );
		$this->assertTrue( is_array( $data ) );
		$this->assertArrayHasKey( 'latitude', $data );
		$this->assertArrayHasKey( 'longitude', $data );
	}
}
