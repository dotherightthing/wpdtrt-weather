<?php
/**
 * Template to display blocks in shortcodes and widgets
 *
 * @package     wpdtrt_weather
 * @since       1.0.0
 * @version     1.0.0
 *
 * @todo Is this query var seen by other plugins which also use this class?
 */

// Predeclare variables

// Internal WordPress arguments available to widgets
// This allows us to use the same template for shortcodes and front-end widgets
$before_widget = null; // register_sidebar
$before_title = null; // register_sidebar
$title = null;
$after_title = null; // register_sidebar
$after_widget = null; // register_sidebar

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
$min =      $plugin->get_api_day_min();
$max =      $plugin->get_api_day_max();
$icon =     $plugin->get_api_day_icon();
$summary =  $plugin->get_api_day_summary();
$unit =     $plugin->get_api_day_unit();

// WordPress widget options (widget, not shortcode)
echo $before_widget;
echo $before_title . $title . $after_title;
?>

<?php
  if ( isset( $min, $max ) ):
?>

<<?php echo $element; ?> title="<?php echo $summary; ?>">
  <?php echo $icon; ?>
  <span class=wpdtrt-plugin-screen-reader-text"><?php echo $summary; ?></span>
  <?php if ( $max > $min ): ?>
    <?php echo $min; ?> - <?php echo $max; ?>
  <?php else: ?>
    <?php echo $max; ?>
  <?php endif; ?>
  <?php echo $unit; ?>
</<?php echo $element; ?>>

<?php
  endif;

  // output widget customisations (not output with shortcode)
  echo $after_widget;
?>
