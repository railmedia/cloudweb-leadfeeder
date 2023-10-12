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
	'class-service.php'	 => [],
	'class-rest.php'	 => [ 'CW_LF_OSA\REST' ],
	'class-admin.php'	 => [ 'CW_LF_OSA\Admin' ],
    'class-frontend.php' => [ 'CW_LF_OSA\Frontend' ],
	'updates.php'		 => [ 'CW_LF_OSA\Updates' ]
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
