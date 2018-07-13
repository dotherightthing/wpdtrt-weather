/**
 * @file DTRT Weather frontend.js
 * @summary
 *     Front-end scripting for public pages
 *     PHP variables are provided in `wpdtrt_weather_config`.
 * @version 0.0.1
 * @since   0.7.0 DTRT WordPress Plugin Boilerplate Generator
 */

/* eslint-env browser */
/* global document, jQuery, wpdtrt_weather_config */
/* eslint-disable no-unused-vars */

/**
 * @namespace wpdtrt_weather_ui
 */
const wpdtrt_weather_ui = {

    /**
     * Initialise front-end scripting
     * @since 0.0.1
     */
    init: () => {
        "use strict";

        console.log("wpdtrt_weather_ui.init");
    }
}

jQuery(document).ready( ($) => {

    "use strict";

    const config = wpdtrt_weather_config;
    wpdtrt_weather_ui.init();
});

/* eslint-enable no-unused-vars */
