<?php
/**
*
* Uninstall workflow
*
* @package cloudWEB LeadFeeder and OneSimpleApi
* @author  Adrian Emil Tudorache
* @license GPL-2.0+
* @link    https://www.tudorache.me/
**/

if ( ! defined( 'ABSPATH' ) ) {
    header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

function cwlfosa_uninstall() {

    $options = [
        'cwlfosa_leadfeeder_tracker_script_id',
        'cwlfosa_onesimpleapi_api_key',
        'cwlfosa_paragraph_selector',
        'cwlfosa_paragraph_template',
        'cwlfosa_image_selector',
        'cwlfosa_matching_pages'
    ];

    foreach( $options as $option ) {
        delete_option( $option );
    }

}
?>
