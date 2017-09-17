<?php
/**
 * Template partial for Admin Options page
 *    WP Admin > Settings > DTRT Weather
 *
 * This file contains PHP, and HTML from the WordPress_Admin_Style plugin.
 *
 * @link        https://github.com/dotherightthing/wpdtrt-weather
 * @link        /wp-admin/admin.php?page=WordPress_Admin_Style#twocolumnlayout2
 * @since       0.1.0
 *
 * @package     WPDTRT_Weather
 * @subpackage  WPDTRT_Weather/templates
 */
?>

<div class="wrap">

  <div id="icon-options-general" class="icon32"></div>
  <h1><?php esc_attr_e( 'DTRT Weather', 'wp_admin_style' ); ?></h1>

  <div id="poststuff">

    <div id="post-body" class="metabox-holder columns-2">

      <!-- main content -->
      <div id="post-body-content">

        <div class="meta-box-sortables ui-sortable">

          <div class="postbox">

            <h2>
              <span><?php esc_attr_e( 'Options', 'wp_admin_style' ); ?></span>
            </h2>

            <div class="inside">

              <form name="wpdtrt_weather_data_form" method="post" action="">

                <input type="hidden" name="wpdtrt_weather_form_submitted" value="Y" />

                <table class="form-table">
                  <tr>
                    <th>
                      <label for="wpdtrt_weather_api_key">Your Darksky API key:</label>
                    </th>
                    <td>
                      <input type="text" name="wpdtrt_weather_api_key" id="wpdtrt_weather_api_key" value="<?php echo $wpdtrt_weather_api_key; ?>">
                    </td>
                  </tr>
                </table>

                <?php
                /**
                 * submit_button( string $text = null, string $type = 'primary', string $name = 'submit', bool $wrap = true, array|string $other_attributes = null )
                 */
                  submit_button(
                    $text = 'Save',
                    $type = 'primary',
                    $name = 'wpdtrt_weather_submit',
                    $wrap = true,
                    $other_attributes = null
                  );
                ?>

              </form>
            </div>
            <!-- .inside -->

          </div>
          <!-- .postbox -->

        </div>
        <!-- .meta-box-sortables .ui-sortable -->

      </div>
      <!-- post-body-content -->


    </div>
    <!-- #post-body .metabox-holder .columns-2 -->

    <br class="clear">
  </div>
  <!-- #poststuff -->

</div> <!-- .wrap -->
