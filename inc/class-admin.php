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

class Admin {

    /**
	 * Saved Settings
	 * @var array
	 */
	private $settings = array();

    /**
	 * Constructor
	 */
    function __construct() {
        $this->settings = Service::get_main_settings();
        add_action( 'admin_menu', [ $this, 'menu' ] );
        add_action( 'admin_init', [ $this, 'display_settings' ] );
    }

    function menu() {

        add_menu_page(
            __( 'cloudWEB LeadFeeder', 'cw-lf-osa' ),
            'cloudWEB LeadFeeder',
            'manage_options',
            'cloudweb-leadfeeder-onesimpleapi',
            [ $this, 'settings'],
            'dashicons-share',
            50
        );

    }

    function settings() {

        $tabs = [
			[
				'id' => 'main',
				'label' => __( 'Settings', 'cw-lf-osa' )
            ],
        ];

        $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'main'

    ?>
    <div class="wrap">
        <h1><?php _e( 'cloudWEB LeadFeeder and OneSimpleApi', 'cw-lf-osa' ); ?></h1>
		<h2 class="nav-tab-wrapper">
			<?php foreach( $tabs as $tab ) { ?>
			<a
				href="<?php echo admin_url(); ?>admin.php?page=cloudweb-leadfeeder-onesimpleapi&tab=<?php echo $tab['id']; ?>"
				class="nav-tab <?php echo $active_tab == $tab['id'] ? 'nav-tab-active' : ''; ?>"
			>
				<?php echo $tab['label']; ?>
			</a>
			<?php } ?>
        </h2>
        <form action="<?php echo admin_url(); ?>options.php" method="POST">
        <?php
			if( $active_tab == 'main' ) {
            	settings_fields('cwlfosa_settings');
            	do_settings_sections('cwlfosa');
				submit_button();
			}
        ?>
        </form>
    </div>
    <?php
    }

    function settings_field( $args ) {

		$settings = array_merge( $this->settings );

		$value = isset( $settings[ $args['id'] ] ) ? $settings[ $args['id'] ] : null;

		switch( $args['type'] ) {

			case 'text': case 'number': case 'email':
			?>
			<input id="<?php echo esc_html( $args['id'] ); ?>" style="<?php echo isset( $args['style'] ) && $args['style'] ? $args['style'] : ''; ?>" type="<?php echo $args['type']; ?>" name="<?php echo esc_html( $args['id'] ) ?>" value="<?php echo $value; ?>" />
			<?php
			break;
			case 'textarea':
			?>
			<textarea style="<?php echo isset( $args['style'] ) && $args['style'] ? $args['style'] : ''; ?>" name="<?php echo esc_html( $args['id'] ); ?>"><?php echo $value; ?></textarea>
			<?php
			break;
			case 'select':
			?>
			<select name="<?php echo esc_html( $args['id'] ); ?>">
				<?php if( isset( $args['default'] ) && $args['default'] ) { ?>
	            <option value=""><?php echo esc_html( $args['default'] ); ?></option>
				<?php } ?>
	            <?php foreach( $args['options'] as $opt_value => $opt_label ) { ?>
	            <option <?php echo $value == $opt_value ? 'selected="selected"' : ''; ?> value="<?php echo $opt_value; ?>"><?php echo $opt_label; ?></option>
	            <?php } ?>
	        </select>
			<?php
			break;
			case 'range':

			$min = isset( $args['min'] ) ? $args['min'] : 0;
			$max = isset( $args['max'] ) ? $args['max'] : 50;
			?>
			<input
				type="range"
				id="<?php echo $args['id']; ?>"
				name="<?php echo esc_html( $args['id'] ); ?>"
				value="<?php echo $value; ?>"
				min="<?php echo $min; ?>"
				max="<?php echo $max; ?>"
				step="1"
				style="<?php echo isset( $args['style'] ) && $args['style'] ? $args['style'] : ''; ?>"
			/>
			<?php
			break;
			case 'title':
			break;
			default:

		}

		if( isset( $args['desc'] ) && $args['desc'] ) {
		?>
			<p><small><em><?php echo $args['desc']; ?></em></small></p>
		<?php
		}

	}

    /**
    * Display settings on Settings page
    */
    function display_settings() {

        $settings_sections = [

			[
				'id' => 'cwlfosa_settings',
				'label' => __( 'Main Configuration', 'cwlfosa' ),
				'page' => 'cwlfosa',
				'options' => [
                    [
						'id' 	   => 'cwlfosa_leadfeeder_tracker_script_id',
						'name' 	   => __( 'Leadfeeder tracker script ID', 'cwlfosa' ),
						'callback' => [ $this, 'settings_field' ],
						'args'	   => [
							'id'   => 'cwlfosa_leadfeeder_tracker_script_id',
							'type' => 'text',
                            'style' => 'width: 25%;',
                            'desc' => sprintf( __( 'Get from %s', 'cwlfosa' ), '<a href="https://app.dealfront.com/f/settings/company/143107/tracking/script" target="_blank" rel="noopener noreferrer">LeadFeeder Settings</a>' )
                        ]
                    ],
                    [
						'id' 	   => 'cwlfosa_onesimpleapi_api_key',
						'name' 	   => __( 'OneSimpleApi API key', 'cwlfosa' ),
						'callback' => [ $this, 'settings_field' ],
						'args'	   => [
							'id'   => 'cwlfosa_onesimpleapi_api_key',
							'type' => 'text',
                            'style' => 'width: 25%;',
                            'desc' => sprintf( __( 'Get from %s', 'cwlfosa' ), '<a href="https://onesimpleapi.com/user/api-tokens" target="_blank" rel="noreferrer noopener">OneSimpleApi settings</a>' )
                        ]
                    ],
                    [
						'id' 	   => 'cwlfosa_paragraph_selector',
						'name' 	   => __( 'Paragraph selector', 'cwlfosa' ),
						'callback' => [ $this, 'settings_field' ],
						'args'	   => [
							'id'   => 'cwlfosa_paragraph_selector',
							'type' => 'text',
                            'style' => 'width: 25%;',
                            'desc' => __( 'Needs to be part of the DOM and preferably it needs to be a unique ID', 'cwlfosa' )
                        ]
                    ],
					[
						'id' 	   => 'cwlfosa_paragraph_template',
						'name' 	   => __( 'Paragraph template text', 'cwlfosa' ),
						'callback' => [ $this, 'settings_field' ],
						'args'	   => [
							'id'   => 'cwlfosa_paragraph_template',
							'type' => 'textarea',
                            'style' => 'width: 25%; height: 150px',
                            'desc' => __( 'You can use the following tags: %branch%, %minmax%', 'cwlfosa' )
                        ]
                    ],
                    [
						'id' 	   => 'cwlfosa_image_selector',
						'name' 	   => __( 'Image selector', 'cwlfosa' ),
						'callback' => [ $this, 'settings_field' ],
						'args'	   => [
							'id'   => 'cwlfosa_image_selector',
							'type' => 'text',
                            'style' => 'width: 25%;',
                            'desc' => __( 'Needs to be part of the DOM and preferably it needs to be a unique ID', 'cwlfosa' )
                        ]
                    ],
                    [
						'id' 	   => 'cwlfosa_matching_pages',
						'name' 	   => __( 'Matching pages', 'cwlfosa' ),
						'callback' => [ $this, 'settings_field' ],
						'args'	   => [
							'id'   => 'cwlfosa_matching_pages',
							'type' => 'text',
                            'style' => 'width: 25%;',
                            'desc' => __( 'Add URLs separated by comma', 'cwlfosa' )
                        ]
                    ],
                ]
            ]

        ];

        foreach( $settings_sections as $section ) {

			add_settings_section( $section['id'], $section['label'], null, $section['page'] );

			foreach( $section['options'] as $option ) {

				register_setting( $section['id'], $option['id'] );

				add_settings_field(
		            $option['id'],
		            $option['name'],
		            $option['callback'],
		            $section['page'],
		            $section['id'],
		            $option['args'] ? $option['args'] : array()
		        );

			}

		}

    }

}
?>