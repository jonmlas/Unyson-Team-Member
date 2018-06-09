<?php
/*
Plugin Name: Unyson Team Member
Plugin URI: https://github.com/jonmlas/Unyson-Team-Member
Description: Adds Team Member Post Type and Shortcode for Unyson Framework
Version: 1.0.0
Author: Jon-Michael Lastimosa
Author URI: http://jon-michael.lastimosa.com.ph
Text Domain: lastimosa-team-member
Domain Path: /languages/
*/

//plugin language file
function lastimosa_team_member_lang(){
    load_plugin_textdomain('lastimosa-team-member', false, basename( dirname( __FILE__ ) ) . '/languages');
}
add_action('admin_head','lastimosa_team_member_lang');


function _filter_lastimosa_team_member_extension($locations) {
    $locations[dirname(__FILE__) . '/extensions'] = dirname( __FILE__ ) . 'extensions';
    return $locations;
}
add_filter('fw_extensions_locations', '_filter_lastimosa_team_member_extension');