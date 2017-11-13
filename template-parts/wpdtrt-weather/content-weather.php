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

$plugin_data = $plugin->get_plugin_data();
$plugin_options = $plugin->get_plugin_options();

$google_maps_api_key = $plugin_options['google_maps_api_key'];

// WordPress widget options (widget, not shortcode)
echo $before_widget;
echo $before_title . $title . $after_title;
?>

<?php
  if ( ! empty($plugin_data) ):

    foreach( $plugin_data as $key => $val ):

      $data_object =   $plugin_data[$key];
      $min =           $plugin->get_min( $data_object );
      $max =           $plugin->get_max( $data_object );
      $summary =       $plugin->get_summary( $data_object );
      $icon =          $plugin->get_icon( $data_object );
      $unit =          $plugin->get_unit( $data_object );

      if ( isset( $min, $max ) ): ?>

<<?php echo $element; ?> title="<?php echo $summary; ?>">
  <?php echo $icon; ?>
  <span class="says"><?php echo $summary; ?></span>
  <?php if ( $max > $min ): ?>
    <?php echo $min; ?> - <?php echo $max; ?>
  <?php else: ?>
    <?php echo $max; ?>
  <?php endif; ?>
  <?php echo $unit; ?>
</<?php echo $element; ?>>

<?php
      endif;
    endforeach;
  endif;

  // output widget customisations (not output with shortcode)
  echo $after_widget;
?>
