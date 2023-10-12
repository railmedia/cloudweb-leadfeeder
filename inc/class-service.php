<?php
/**
* @package cloudWEB LeadFeeder and OneSimpleApi
* @author  Adrian Emil Tudorache
* @license GPL-2.0+
* @link    https://www.tudorache.me/
**/

namespace CW_LF_OSA;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

class Service {

    public static function get_db_settings( $fields ) {

		$settings = array();
        foreach( $fields as $setting ) {
            $settings[ $setting ] = get_option( $setting );
        }

        return $settings;

	}

    public static function get_main_settings() {

        $settings_fields = [
            'cwlfosa_leadfeeder_tracker_script_id',
            'cwlfosa_onesimpleapi_api_key',
            'cwlfosa_paragraph_selector',
            'cwlfosa_paragraph_template',
            'cwlfosa_image_selector',
            'cwlfosa_matching_pages'
        ];

        return self::get_db_settings( $settings_fields );

    }

}
?>