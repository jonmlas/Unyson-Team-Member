<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$manifest = array();

$manifest['name']        = __( 'Team Member', 'lastimosa' );
$manifest['description'] = __( 'Adds Team Member Post Type and Shortcode for Unyson Framework', 'lastimosa' );
$manifest['version'] = '1.0.0';
$manifest['display'] = true;
$manifest['standalone'] = true;
$manifest['github_repo'] = 'https://github.com/jonmlas/Unyson-Team-Member';
$manifest['uri'] = 'http://theme.lastimosa.com.ph';
$manifest['author'] = 'Jon-Michael Lastimosa';
$manifest['author_uri'] = 'http://jon-michael.lastimosa.com.ph';

$manifest['github_update'] = 'jonmlas/Unyson-Team-Member';