<?php
/*
Plugin Name: yourPLUGIN
Plugin URI: http://codecanyon.net/item/YOURITEM
Description: Super awesome description.
Version: 1.0.0
Author: yourNAME
Author URI: http://yoursite.com
Text Domain: yourextension
Domain Path: /languages/
*/

//plugin language file
function yourextension_lang(){
    load_plugin_textdomain('yourextension', false, basename( dirname( __FILE__ ) ) . '/languages');
}
add_action('admin_head','yourextension_lang');


function _filter_yourextension_extension($locations) {
    $locations[dirname(__FILE__) . '/extensions'] = dirname( __FILE__ ) . 'extensions';
    return $locations;
}
add_filter('fw_extensions_locations', '_filter_yourextension_extension');