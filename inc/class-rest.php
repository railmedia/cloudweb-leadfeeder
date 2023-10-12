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

class REST {

    function __construct() {
        add_action( 'rest_api_init', array( $this, 'routes' ) );
    }

    function routes() {

		/*=============================
		= Back-end routes
		===============================*/

        $namespace = 'cwlfosa/v1';

		/**
		* Channels settings page - /wp-admin/edit.php?post_type=cw_seo_survey&page=cw-seo-survey-channels
		**/
        register_rest_route( $namespace, '/gdfi', array(
            'methods' => \WP_REST_Server::CREATABLE,
            'callback' => array( $this, 'get_domain_featured_image' ),
			// 'permission_callback' => function() {
            //     return current_user_can( 'manage_options' );
            // },
			'permission_callback' => '__return_true',
            'args' => array(
                'domain' => array(
					'type'     => 'array',
					'required' => true,
					'validate_callback' => function( $value, $request, $param ) {
                        return is_string( $value );
                    }
                    // 'sanitize_callback' => function( $value, $request, $param ) {
                    //     return sanitize_text_field( $request['amount'] );
                    // }
                )
            )
        ) );

        register_rest_route( $namespace, '/ddfi', array(
            'methods' => \WP_REST_Server::CREATABLE,
            'callback' => array( $this, 'delete_domain_featured_image' ),
			// 'permission_callback' => function() {
            //     return current_user_can( 'manage_options' );
            // },
			'permission_callback' => '__return_true',
            'args' => array(
                'imgName' => array(
					'type'     => 'array',
					'required' => true,
					'validate_callback' => function( $value, $request, $param ) {
                        return is_string( $value );
                    }
                    // 'sanitize_callback' => function( $value, $request, $param ) {
                    //     return sanitize_text_field( $request['amount'] );
                    // }
                )
            )
        ) );

    }

    public function get_domain_featured_image( $request ) {

        $domain = $request['domain'] ? $request['domain'][0] : '';
        $api_key = get_option( 'cwlfosa_onesimpleapi_api_key' );
        if( $domain && $api_key ) {
            $dir = ABSPATH . 'wp-content/uploads/onesimpleapi/';
            if( ! is_dir( $dir ) ) {
                mkdir( $dir, 0777 );
            }
            $img_name = time();
            $image = file_get_contents( 'https://onesimpleapi.com/api/screenshot?token=' . $api_key . '&force=no&output=redirect&url=' . $domain );
            $image = file_put_contents( $dir . $img_name . '.jpg', $image );
        }

        return new \WP_REST_Response([
            'response' => rtrim( site_url(), '/' ) . '/wp-content/uploads/onesimpleapi/' . $img_name . '.jpg',
            'imgName'  => $img_name . '.jpg'
        ], 200);

    }

    public function delete_domain_featured_image( $request ) {

        $img_name = $request['imgName'];
        $img_name = $img_name ? $img_name[0] : '';

        if( $img_name ) {
            
            $dir = ABSPATH . 'wp-content/uploads/onesimpleapi/';
            if( file_exists( $dir . $img_name ) ) {
                unlink( $dir . $img_name );
            }

        }

        return new \WP_REST_Response([], 200);
        
    }

}
?>