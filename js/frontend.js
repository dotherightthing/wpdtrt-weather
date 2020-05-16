/**
 * @file DTRT Weather frontend.js
 * @summary Front-end scripting for public pages.
 * @description PHP variables are provided in `wpdtrt_weather_config`.
 * @version 0.0.1
 * @since   0.7.0
 */

/* eslint-env browser */
/* global jQuery, wpdtrt_weather_config */
/* eslint-disable no-unused-vars */

/**
 * @namespace wpdtrtWeatherUi
 */
const wpdtrtWeatherUi = {

    /**
     * @summary Initialise front-end scripting
     * @since 0.0.1
     */
    init: () => {
        console.log('wpdtrtWeatherUi.init'); // eslint-disable-line no-console
    }
};

jQuery(document).ready(($) => {
    const config = wpdtrt_weather_config; // eslint-disable-line
    wpdtrtWeatherUi.init();
});

/* eslint-enable no-unused-vars */
