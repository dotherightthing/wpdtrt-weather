<?php
/**
 * Template to display plugin output in shortcodes and widgets
 *
 * @package   DTRT Weather
 * @version   0.0.1
 * @since     0.7.5 DTRT WordPress Plugin Boilerplate Generator
 */

// Predeclare variables

// Internal WordPress arguments available to widgets
// This allows us to use the same template for shortcodes and front-end widgets
$before_widget = null; // register_sidebar
$before_title  = null; // register_sidebar
$title         = null;
$after_title   = null; // register_sidebar
$after_widget  = null; // register_sidebar

// shortcode options
$element = null;

// access to plugin
$plugin = null;

// Options: display $args + widget $instance settings + access to plugin
$options = get_query_var( 'options' );

// Overwrite variables from array values
// @link http://kb.network.dan/php/wordpress/extract/
extract( $options, EXTR_IF_EXISTS );

// load the data
$plugin->get_api_data();

// Get the day's historical forecast data
$min     = $plugin->get_api_day_min();
$max     = $plugin->get_api_day_max();
$icon    = $plugin->get_api_day_icon();
$summary = $plugin->get_api_day_summary();
$unit    = $plugin->get_api_day_unit();

// WordPress widget options (not output with shortcode)
echo $before_widget;
echo $before_title . $title . $after_title;

if ( isset( $min, $max ) ):
?>

<<?php echo $element; ?> title="<?php echo $summary; ?>" class="wpdtrt-weather">
	<span class="wpdtrt-weather--icon"><?php echo $icon; ?></span>
	<span class="wpdtrt-weather--summary"><?php echo $summary; ?></span>
	<?php if ( $max > $min ): ?>
	<span class="wpdtrt-weather--min"><?php echo $min; ?></span>
	<span class="wpdtrt-weather--range"> / </span>
	<span class="wpdtrt-weather--max"><?php echo $max; ?></span>
	<?php else: ?>
	<span class="wpdtrt-weather--max"><?php echo $max; ?></span>
	<?php endif; ?>
	<span class="wpdtrt-weather--unit"><?php echo $unit; ?></span>
</<?php echo $element; ?>>

<?php
endif;

// output widget customisations (not output with shortcode)
echo $after_widget;
