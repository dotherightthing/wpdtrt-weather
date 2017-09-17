<?php
/**
 * Template partial for the public front-end
 *
 * This file contains PHP, and HTML.
 *
 * @link        https://github.com/dotherightthing/wpdtrt-weather
 * @since       0.1.0
 *
 * @package     WPDTRT_Weather
 * @subpackage  WPDTRT_Weather/templates
 */
?>

<?php
  // output widget customisations (not output with shortcode)
  echo $before_widget;
  echo $before_title . $title . $after_title;
?>

<?php if ( isset( $min, $max ) ): ?>

<<?php echo $element; ?> title="<?php echo $summary; ?>">'
  <?php echo $icon; ?>
  <span class="says"><?php echo $summary; ?></span>
  <?php if ( $max > $min ): ?>
    <?php echo $min; ?> - <?php echo $max; ?>
  <?php else: ?>
    <?php echo $max; ?>
  <?php endif; ?>
  <?php echo $unit; ?>
</<?php echo $element; ?>>

<?php endif; ?>

<?php
  // output widget customisations (not output with shortcode)
  echo $after_widget;
?>
