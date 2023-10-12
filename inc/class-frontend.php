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

class Frontend {

    function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ] );
        add_action( 'wp_head', [ $this, 'head' ] );
    }

    function scripts() {

        global $wp;

        $settings = [ 'nonce' => wp_create_nonce('wp_rest') ];
        $settings_slugs = [ 'cwlfosa_leadfeeder_tracker_script_id', 'cwlfosa_paragraph_selector', 'cwlfosa_image_selector', 'cwlfosa_paragraph_template' ];
        foreach( $settings_slugs as $slug ) {
            $settings[ $slug ] = get_option( $slug );
        }

        wp_register_script( 'cwlfosa', CWLFOSAURL . 'assets/scripts/build/front.bundle.js', array(), null, true );
        wp_localize_script( 'cwlfosa', 'cwlfosa', $settings );

        $matching_pages = get_option( 'cwlfosa_matching_pages' );

        if( $matching_pages ) {
            $matching_pages = array_flip( explode( ',', $matching_pages ) );
            if( isset( $matching_pages[ home_url( $wp->request ) ] ) ) {
                wp_enqueue_script( 'cwlfosa' );
            }
        }

    }

    function head() {

        $script_id = get_option( 'cwlfosa_leadfeeder_tracker_script_id' );
        $script_id = explode('_', $script_id);
        if( isset( $script_id[1] ) ) {
    ?>
        <script>(function(ss,ex){ window.ldfdr=window.ldfdr||function(){(ldfdr._q=ldfdr._q||[]).push([].slice.call(arguments));}; (function(d,s){ fs=d.getElementsByTagName(s)[0]; function ce(src){ var cs=d.createElement(s); cs.src=src; cs.async=1; fs.parentNode.insertBefore(cs,fs); }; ce('https://sc.lfeeder.com/lftracker_v1_'+ss+(ex?'_'+ex:'')+'.js'); })(document,'script'); })('<?php echo $script_id[1]; ?>');</script>
    <?php
        }

    }

}
?>