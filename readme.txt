
=== DTRT Weather ===
Contributors: dotherightthingnz
Donate link: http://dotherightthing.co.nz
Tags: weather, forecast, GPS
Requires at least: 4.9.5
Tested up to: 4.9.5
Requires PHP: 5.6.30
Stable tag: 0.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Displays historical weather information for the GPS location determined by the Featured Image.

== Description ==

Displays historical weather information for the GPS location determined by the Featured Image.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/wpdtrt-weather` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->DTRT Weather screen to configure the plugin

== Frequently Asked Questions ==

= How do I use the widget? =

One or more widgets can be displayed within one or more sidebars:

1. Locate the widget: Appearance > Widgets > *DTRT Weather Widget*
2. Drag and drop the widget into one of your sidebars
3. Add a *Title*
4. Specify *Element*

= How do I use the shortcode? =

```
<!-- within the editor -->
[wpdtrt_weather_shortcode_1 element="dd"]

// in a PHP template, as a template tag
<?php echo do_shortcode( '[wpdtrt_weather_shortcode_1 element="dd"]' ); ?>
```

Please refer to the *Shortcode Options* on Settings-><%= nameFriendly %>.

== Screenshots ==

1. The caption for ./images/screenshot-1.(png|jpg|jpeg|gif)
2. The caption for ./images/screenshot-2.(png|jpg|jpeg|gif)

== Changelog ==

= 0.0.1 =
* Initial version

== Upgrade Notice ==

= 0.0.1 =
* Initial release
