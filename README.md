# DTRT Weather

[![GitHub release](https://img.shields.io/github/release/dotherightthing/wpdtrt-weather.svg)](https://github.com/dotherightthing/wpdtrt-weather/releases) [![Build Status](https://github.com/dotherightthing/wpdtrt-weather/workflows/Build%20and%20release%20if%20tagged/badge.svg)](https://github.com/dotherightthing/wpdtrt-weather/actions?query=workflow%3A%22Build+and+release+if+tagged%22) [![GitHub issues](https://img.shields.io/github/issues/dotherightthing/wpdtrt-weather.svg)](https://github.com/dotherightthing/wpdtrt-weather/issues)

Displays historical weather information for the GPS location determined by the Featured Image.

## Setup and Maintenance

Please read [DTRT WordPress Plugin Boilerplate: Workflows](https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/wiki/Workflows).

## WordPress Installation

Please read the [WordPress readme.txt](readme.txt).

## WordPress Usage

### Shortcode

Within the editor:

```txt
[wpdtrt_weather_shortcode_1 element="dd"]
```

In a PHP template, as a template tag:

```php
<?php echo do_shortcode( '[wpdtrt_weather_shortcode_1 element="dd"]' ); ?>
```

### Styling

Core CSS properties may be overwritten by changing the variable values in your theme stylesheet.

See `scss/variables/_css.scss`.
