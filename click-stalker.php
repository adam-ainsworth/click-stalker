<?php
/**
 * Click Stalker
 *
 * Plugin Name:       Click Stalker
 * Plugin URI:        https://adamainsworth.co.uk/plugins/
 * Description:       Allows you to add arbitrary tracking code to downloadable assets that a user can click on.
 * Version:           1.0.0
 * Author:            Adam Ainsworth
 * Author URI:        https://adamainsworth.co.uk/
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       click-stalker
 * Domain Path:       /languages
 * Requires at least: 2.7.0
 * Tested up to:      5.8.1
 */

 // redirect if some comes directly
if ( ! defined( 'WPINC' ) && ! defined( 'ABSPATH' ) ) {
	header('Location: /'); die;
}

// check that we're not defined somewhere else
if ( ! class_exists( 'Click_Stalker' ) ) {
	class Click_Stalker {
		private function __construct() {}

		public static function activate() {
	        if ( ! current_user_can( 'activate_plugins' ) ) {
				return;
			}

			// any activation code here
		}

		public static function deactivate() {
	        if ( ! current_user_can( 'activate_plugins' ) ) {
				return;
			}

			// any deactivation code here
		}

		public static function uninstall() {
	        if ( ! current_user_can( 'activate_plugins' ) ) {
				return;
			}

			if ( __FILE__ !== WP_UNINSTALL_PLUGIN ) {
				return;
			}
			 
			$option_name = 'click_stalker_options';
			delete_option($option_name);
			delete_site_option($option_name);
		}

		public static function init() {
			add_action( 'parse_request', [__CLASS__, 'click_tracker'] );
			add_action( 'wp_head', [__CLASS__, 'click_tracker_converter'] );
			add_action( 'wp_enqueue_scripts', [__CLASS__, 'scripts'] );


			add_filter( 'plugin_action_links', [__CLASS__, 'add_links'], 10, 2 );
			add_action( 'admin_menu', [__CLASS__, 'add_admin_menu'] );
			add_action( 'admin_init', [__CLASS__, 'options_init'] );
		}

		function scripts() {
			wp_deregister_script('jquery');
			wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js', array(), null, true);
		}

		// This code intercepts all calls to /click, extracts the target URI, fires the JS click tracker and then forwards 
		public static function click_tracker() {
			$uri = rtrim($_SERVER['REQUEST_URI'], '/' );
			$prefix = '/wp-content/uploads';
			$click_prefix = '/click';
			$options = get_option( 'click_stalker_options' );
			$tracking_code = $options['tracking_code'];

			// is it a call to the click URI?
			if( $tracking_code && strpos( strtolower( $uri ), $click_prefix ) === 0 ) {
				// if so, extract the destination URI
				$forward_path = $prefix . substr($uri, strlen($click_prefix) );

				// the fire the click tracker and set a JS forwarder to the destination

				echo($tracking_code); // THIS IS SPECIFICALLY OUTPUTTING HTML, JS ETC SO MUST REMAIN UNESCAPED
			?>
				<p>Redirecting...</p>
				<script>window.addEventListener('load', function () { setTimeout( function(){ window.location.replace('<?php echo($forward_path); ?>'); }, 100); } );</script>	
			<?php 
				// TODO - options page
				exit();
			}
		}

			// uses jQuery to dynamically convert all A tags on a page that go to items in the uploads to redirect via the click tracker
			// should work with any method of putting assets on the page

			// jqueryDefer() allows you to wait for jQuery to load before running the function. This means it is a early as possible
			public static function click_tracker_converter() {
			?>
				<script>
					function jqueryDefer(method) {
						if (window.$) {
							method(window);
						} else {
							setTimeout(function() { jqueryDefer(method) }, 50);
						}
					}

					jqueryDefer(
						function() {
							$(document).ready( function() {
								$('a').each(function() {
									var uri = $(this).attr('href'), prefix = '/wp-content/uploads', click_prefix = '/click';
									
									if( uri.startsWith(prefix) ) {
										$(this).attr('href', click_prefix + uri.substring(prefix.length) + '/', '_blank');
									}
								});
							});
						}
					);
				</script>
			<?php
		}
				
		// add links to section on plugins page
		public static function add_links( $links, $file ) {
			if ( $file === 'click-stalker/click-stalker.php' && current_user_can( 'manage_options' ) ) {	
				
				$links = (array) $links;
				$links[] = sprintf( '<a href="%s">%s</a>', admin_url( 'options-general.php?page=click_stalker' ), __( 'Settings', 'click-stalker' ) );
			}

			return $links;
		}
		
		// add the item to the admin menu
		public static function add_admin_menu() { 
			add_options_page(
				'Click Stalker',
				'Click Stalker',
				'manage_options',
				'click_stalker',
				[__CLASS__, 'options_page_render']
			);
		}
		
		// set up options and settings fields
		public static function options_init() { 
			register_setting( 'click_stalker_options', 'click_stalker_options' );
		
			add_settings_section(
				'click_stalker_options_section', 
				__( '', 'click-stalker' ), 
				[__CLASS__, 'settings_render'], 
				'click_stalker_options'
			);
		
			add_settings_field( 
				'tracking_code', 
				__( 'Tracking Code', 'click-stalker' ), 
				[__CLASS__, 'tracking_code_render'], 
				'click_stalker_options', 
				'click_stalker_options_section' 
			);
		}
		
		// render options page
		public static function options_page_render() { 
			?>
				<form action='options.php' method='post'>		
					<h2>Click Stalker</h2>
		
					<?php
						settings_fields( 'click_stalker_options' );
						do_settings_sections( 'click_stalker_options' );
						submit_button();
					?>
				</form>
			<?php
		}
		
		// render settings section
		public static function settings_render() { 
			?><p><strong><?php echo __( 'Add the HTML for your tracking code below.', 'click-stalker' ); ?></strong></p><?php
		}
		
		// render settings fields
		public static function tracking_code_render() {
			$options = get_option( 'click_stalker_options' );
			 // THIS IS SPECIFICALLY OUTPUTTING HTML, JS ETC SO MUST REMAIN UNESCAPED
			?>
			<textarea cols='80' rows='5' name='click_stalker_options[tracking_code]'><?php echo $options['tracking_code']; ?></textarea>
			<?php
		}		
	}

	register_activation_hook( __FILE__, [ 'Click_Stalker', 'activate' ] );
	register_deactivation_hook( __FILE__, [ 'Click_Stalker', 'deactivate' ] );
	register_uninstall_hook( __FILE__, [ 'Click_Stalker', 'uninstall' ] );
	add_action( 'plugins_loaded', [ 'Click_Stalker', 'init' ] );
}
