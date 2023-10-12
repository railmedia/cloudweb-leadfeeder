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

// Set includes
$files = array(
	'class-service.php'		 => [],
	'class-rest.php'		 => ['CW_LF_OSA\REST'],
    // 'class-cpt-tax.php' 	 => array( 'CW_SEO_Survey\CW_SEO_Survey_CPT_Tax' ),
	'class-admin.php'		 => [ 'CW_LF_OSA\Admin' ],
    'class-frontend.php'		 => [ 'CW_LF_OSA\Frontend' ],
	// 'class-config.php'		 => array( 'CW_SEO_Survey\CW_SEO_Survey_Config' ),
	// 'shortcodes.php'		 => array(),
	// 'updates.php'			 => array()
);

//Include files
foreach( $files as $file => $classes ) {

    require_once( __DIR__ . '/' . $file );
    if( $classes ) {
        foreach( $classes as $class ) {
            new $class;
        }
    }

}

?>
