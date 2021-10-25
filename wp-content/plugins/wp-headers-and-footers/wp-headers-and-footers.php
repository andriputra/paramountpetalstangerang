<?php
/**
 * Plugin Name: WP Headers And Footers
 * Plugin URI: https://www.WPBrigade.com/wordpress/plugins/wp-headers-and-footers/
 * Description: Allows you to insert code or text in the header or footer of your WordPress site.
 * Version: 1.3.0
 * Author: WPBrigade
 * Author URI: https://wpbrigade.com/?utm_source=plugin-meta&utm_medium=author-uri-link
 * License: GPLv3
 * Text Domain: wp-headers-and-footers
 * Domain Path: /languages
 *
 * @package wp-headers-and-footers
 * @category Core
 * @author WPBrigade
 */


if ( ! class_exists( 'WPHeaderAndFooter' ) ) :

	final class WPHeaderAndFooter {

		/**
		 * @var string
		 */
		public $version = '1.3.0';

		/**
		 * @var The single instance of the class
		 * @since 1.0.0
		 */

		protected static $_instance = null;

		/*
		* * * * * * * * *
		* Class constructor
		* * * * * * * * * */
		public function __construct() {

			$this->define_constants();
			$this->includes();
			$this->_hooks();

		}

		/**
		 * Include required core files used in admin and on the frontend.
		 */

		public function includes() {

			include_once WPHEADERANDFOOTER_DIR_PATH . 'classes/class-setup.php';
			include_once WPHEADERANDFOOTER_DIR_PATH . 'classes/plugin-meta.php';
		}

		/**
		 * Hook into actions and filters
		 *
		 * @since  1.0.0
		 * 
		 * @version 1.2.3
		 * 
		 */
		private function _hooks() {

			add_action( 'plugins_loaded', array( $this, 'textdomain' ) );
			add_action( 'admin_enqueue_scripts', array( $this, '_admin_scripts' ) );
			add_action( 'wp_print_scripts', array( $this, '_admin_scripts' ) );
			add_action( 'wp_head', array( $this, 'frontendHeader' ) );
						
			if( function_exists( 'wp_body_open' ) && version_compare( get_bloginfo( 'version' ), '5.2', '>=' ) ) {
				add_action( 'wp_body_open', array( $this, 'frontendBody' ) );
			}
			
			add_action( 'wp_footer', array( $this, 'frontendFooter' ) );
		}

		/**
		 * Define WP Header and Footer Constants
		 */
		private function define_constants() {

			$this->define( 'WPHEADERANDFOOTER_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
			$this->define( 'WPHEADERANDFOOTER_DIR_PATH', plugin_dir_path( __FILE__ ) );
			$this->define( 'WPHEADERANDFOOTER_DIR_URL', plugin_dir_url( __FILE__ ) );
			$this->define( 'WPHEADERANDFOOTER_ROOT_PATH', dirname( __FILE__ ) . '/' );
			$this->define( 'WPHEADERANDFOOTER_VERSION', $this->version );
			$this->define( 'WPHEADERANDFOOTER_FEEDBACK_SERVER', 'https://wpbrigade.com/' );
		}

		function _admin_scripts( $page ) {

			if ( 'settings_page_wp-headers-and-footers' === $page ) {

				wp_enqueue_style( 'wpheaderandfooter_stlye', plugins_url( 'asset/css/style.css', __FILE__ ), array(), WPHEADERANDFOOTER_VERSION );

				$editor_args = array( 'type' => 'text/html' );

				if ( ! current_user_can( 'unfiltered_html' ) || ! current_user_can( 'manage_options' ) ) {
					$editor_args['codemirror']['readOnly'] = true;
				}
		
				// Enqueue code editor and settings for manipulating HTML.
				$settings = wp_enqueue_code_editor( $editor_args );
		
				// Bail if user disabled CodeMirror.
				if ( false === $settings ) {
					return;
				}

				wp_enqueue_script( 'wpheaderandfooter_script', plugins_url( 'asset/js/script.js', __FILE__ ), array( 'jquery' ), WPHEADERANDFOOTER_VERSION );				

			}
		}

		/**
		 * Define constant if not already set
		 *
		 * @param  string      $name
		 * @param  string|bool $value
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * Main Instance
		 *
		 * @since 1.0.0
		 * @static
		 * @see wpheaderandfooter_loader()
		 * @return Main instance
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}


		/**
		 * Load Languages
		 *
		 * @since 1.0.0
		 */
		public function textdomain() {

			$plugin_dir = dirname( plugin_basename( __FILE__ ) );
			load_plugin_textdomain( 'wp-headers-and-footers', false, $plugin_dir . '/languages/' );
		}

		/**
		 * Outputs script / CSS to the header
		 * @since 1.0.0
		 * 
		 * @version 1.2.3
		 */
		function frontendHeader() {
			$this->_output( 'wp_header_textarea' );
		}

		/**
		* Outputs script / CSS to the frontend below opening body
		* @since 1.0.0
		*
		* @version 1.2.3
		*/
		function frontendBody() {
			$this->_output( 'wp_body_textarea' );
		}
		
		/**
		 * Outputs script / CSS to the footer
		 * @since 1.0.0
		 * 
		 * @version 1.0.0
		 */
		function frontendFooter() {
			$this->_output( 'wp_footer_textarea' );
		}

		/**
		 * Outputs the given setting, if conditions are met
		 *
		 * @param string $script Setting Name
		 * @return output
		 */
		function _output( $script ) {

			// Ignore admin, feed, robots or trackbacks
			if ( is_admin() || is_feed() || is_robots() || is_trackback() ) :
				return;
			endif;

			// Get meta
			$_wphaf_setting = get_option( 'wpheaderandfooter_basics' );
			$meta           = ! empty( $_wphaf_setting[ $script ] ) ? $_wphaf_setting[ $script ] : false;

			if ( trim( $meta ) == '' || ! $meta ) :
				return;
			endif;

			// Output
			echo wp_unslash( $meta ) . PHP_EOL;

		}

	}

endif;


/**
 * Returns the main instance of WP to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return WPHeaderAndFooter
 */
function wpheaderandfooter_loader() {
	return WPHeaderAndFooter::instance();
}

// Call the function
wpheaderandfooter_loader();
new WPHeaderAndFooter_Setting();
