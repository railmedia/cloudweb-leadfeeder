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

class Updates {

    private $update_url;

    private $transient_name;

    private $plugin_path;

    private $plugin_base;

    function __construct() {
        
        // $this->update_url = 'http://extserver.local/extension/woocommerce-cembrapay'; //Dev only
        $this->update_url     = 'https://www.thenextstep.ch/extension/cloudweb-leadfeeder-and-onesimpleapi';

        $this->transient_name = 'cwlfosa_update';

        $this->plugin_path 	  = CWLFOSAPATH . 'cloudweb-leadfeeder.php';

        $this->plugin_slug    = 'cloudweb-leadfeeder/cloudweb-leadfeeder.php';

        $this->plugin_base	  = CWLFOSABASE;

        add_filter( 'plugins_api_result', array( $this, 'cwlfosa_plugin_info' ), 10, 3);
        add_filter( 'site_transient_update_plugins', array( $this, 'cwlfosa_update' ) );
    }

    function cwlfosa_plugin_info( $res, $action = null, $args = null ) {

        // Check if this plugins API is about this plugin
        if( $args->slug != $this->plugin_slug ) {
            return $res;
        }

        if( 'plugin_information' !== $action ) {
            return false;
        }

        if( plugin_basename( $this->plugin_base ) !== $args->slug ) {
            return false;
        }

        $data = get_transient( $this->transient_name );

        if( ! $data ) {

            $remote = wp_remote_get(
                $this->update_url,
                array(
                    'timeout' => 10,
                    'headers' => array(
                        'Accept' => 'application/json'
                    )
                )
            );

            if(
                is_wp_error( $remote ) ||
                200 !== wp_remote_retrieve_response_code( $remote ) ||
                empty( wp_remote_retrieve_body( $remote ) )
            ) {
                return false;
            }

            $data = json_decode( wp_remote_retrieve_body( $remote ) );

            set_transient( $this->transient_name, $data, 24 * HOUR_IN_SECONDS );

        }

        $info = new \stdClass();
        $info->name 		  = $data->name;
        $info->slug 		  = $data->slug;
        $info->author 		  = $data->author;
        $info->author_profile = $data->author_profile;
        $info->version 		  = $data->version;
        $info->tested 		  = $data->tested;
        $info->requires 	  = $data->requires;
        $info->requires_php   = $data->requires_php;
        $info->download_link  = $data->download_url;
        $info->trunk 		  = $data->download_url;
        $info->last_updated   = $data->last_updated;
        $info->sections 	  = array(
            'description'  => $data->sections->description,
            'installation' => $data->sections->installation,
            'changelog'    => $data->sections->changelog
        );
        
        if( ! empty( $data->sections->screenshots ) ) {
            $info->sections[ 'screenshots' ] = $data->sections->screenshots;
        }
    
        $info->banners = array(
            'low'  => isset( $data->banners ) && isset( $data->banners->low ) && $data->banners->low ? $data->banners->low : '',
            'high' => isset( $data->banners ) && isset( $data->banners->high ) && $data->banners->high ? $data->banners->high : ''
        );

        $info->icons = array(
            'default' => isset( $data->icons ) && isset( $data->icons->default ) && $data->icons->default ? $data->icons->default : ''
        );
    
        return $info;

    }

    function cwlfosa_update( $transient ) {
    
        if ( empty( $transient->checked ) ) {
            return $transient;
        }

        $data = get_transient( $this->transient_name );

        if( ! $data ) {
    
            $remote = wp_remote_get(
                $this->update_url,
                array(
                    'timeout' => 10,
                    'headers' => array(
                        'Accept' => 'application/json'
                    )
                )
            );

            if(
                is_wp_error( $remote ) ||
                200 !== wp_remote_retrieve_response_code( $remote ) ||
                empty( wp_remote_retrieve_body( $remote ) )
            ) {
                return $transient;
            }

            $data = json_decode( wp_remote_retrieve_body( $remote ) );

            set_transient( $this->transient_name, $data, 24 * HOUR_IN_SECONDS );

        }
    
        if( ! $data ) {
            return $transient;
        }
    
        $plugin_data = get_plugin_data( $this->plugin_path );
    
        $plugin_version = $plugin_data['Version'];
    
        if(
            $data
            && version_compare( $plugin_version, $data->version, '<' )
            && version_compare( $data->requires, get_bloginfo( 'version' ), '<' )
            && version_compare( $data->requires_php, PHP_VERSION, '<' )
        ) {

            $info = new \stdClass();
            $info->name 		  = $data->name;
            $info->slug 		  = $data->slug;
            $info->author 		  = $data->author;
            $info->author_profile = $data->author_profile;
            $info->plugin 		  = plugin_basename( $this->plugin_path );
            $info->version 		  = $data->version;
            $info->new_version 	  = $data->version;
            $info->tested 		  = $data->tested;
            $info->package 		  = $data->download_url;
            $info->requires 	  = $data->requires;
            $info->download_link  = $data->download_url;
            $info->trunk 		  = $data->download_url;
            $info->last_updated   = $data->last_updated;
            $info->sections 	  = array(
                'description'  => $data->sections->description,
                'installation' => $data->sections->installation,
                'changelog'    => $data->sections->changelog
            );
            
            if( ! empty( $data->sections->screenshots ) ) {
                $info->sections[ 'screenshots' ] = $data->sections->screenshots;
            }
        
            $info->banners = array(
                'low'  => isset( $data->banners ) && isset( $data->banners->low ) && $data->banners->low ? $data->banners->low : '',
                'high' => isset( $data->banners ) && isset( $data->banners->high ) && $data->banners->high ? $data->banners->high : ''
            );

            $info->icons = array(
                'default' => isset( $data->icons ) && isset( $data->icons->default ) && $data->icons->default ? $data->icons->default : ''
            );

            $transient->response[ $info->plugin ] = $info;
    
        }
    
        return $transient;
    
    }

}

?>