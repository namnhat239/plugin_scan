/*! @license
See the license.txt file for licensing information for third-party code that may be used in this file.
Relative to the scripts/ directory, the license.txt file is located at license.txt file .
This file (or the corresponding source JS file) has been modified.
*/
/*
	This file is from the submodule-builder repository.
*/
// External Dependencies
import $ from 'jquery';

// Internal Dependencies
import fields from './custom-fields';

$(window).on('et_builder_api_ready', (event, API) => {
    // Register custom modules
    API.registerModalFields(fields);
});