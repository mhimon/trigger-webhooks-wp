<?php
/** بسم الله الرحمن الرحيم  **
 * Main Plugin File
 *
 * @package TriggerWebhooks
 */

/**
 * Plugin Name:       Trigger Webhooks
 * Plugin URI:        https://ultradevs.com
 * Description:       Trigger Webhooks Description
 * Version: 1.0.0
 * Author:            mhimon
 * Author URI:        https://ultradevs.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       trigger-webhooks
 * Domain Path:       /languages
 */

// If this file is called directly, abort!
defined( 'ABSPATH' ) || exit( 'bYe bYe!' );

// Constant.
define( 'TWH_VERSION', '1.0.0' );
define( 'TWH_NAME', 'Trigger Webhooks' );
define( 'TWH_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'TWH_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'TWH_ASSETS', TWH_DIR_URL . 'assets/' );
define( 'TWH_MENU_SLUG', 'trigger-webhooks' );

/**
 * Require Composer Autoload
 */
require_once TWH_DIR_PATH . 'vendor/autoload.php';

/**
 * Trigger Webhooks class
 */
final class TriggerWebhooks {

	/**
	 * Constructor
	 */
	public function __construct() {

		add_action( 'plugins_loaded', array( $this, 'init' ) );

		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		add_action( 'init', array( $this, 'load_text_domain' ) );

		do_action( 'trigger_webhooks_loaded' );
	}

	/**
	 * Begin execution of the plugin
	 *
	 * @return \TriggerWebhooks
	 */
	public static function run() {
		/**
		 * Instance
		 *
		 * @var boolean
		 */
		static $instance = false;

		if ( ! $instance ) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * Plugin Init
	 */
	public function init() {

		// Helper Class.
		$helper = new TWH\Helper();

		// Assets Manager Class.
		$assets_manager = new TWH\Assets_Manager();

		// Activate.
		$activate = new TWH\Activate();

		// Dashboard.
		$dashboard = new TWH\Admin\Dashboard();

		// Rest API Manager.
		$rest_api_manager = new TWH\API();

		// Trigger WH Class.
		$trigger_wh = new TWH\Trigger();

		if ( is_admin() ) {

			// Ajax Class.
			$ajax = new TWH\Ajax();

			// Activation_Redirect.
			add_action( 'admin_init', array( $activate, 'activation_redirect' ) );

			// Dashboard.
			$dashboard->register();

			// Admin Assets.
			add_action( 'admin_enqueue_scripts', array( $assets_manager, 'admin_assets' ) );

			// Plugin Action Links.
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );

		} else {

			// Frontend Assets.
		}
	}

	/**
	 * The code that runs during plugin activation.
	 */
	/**
	 * Plugin Activation.
	 *
	 * @return void
	 */
	public function activate() {
		$activate = new TWH\Activate();
		$activate->run();
	}

	/**
	 * Loads a plugin’s translated strings.
	 *
	 * @return void
	 */
	public function load_text_domain() {
		load_plugin_textdomain( 'trigger-webhooks', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Plugin Action Links
	 *
	 * @param array $links Links.
	 * @return array
	 */
	public function plugin_action_links( $links ) {

		$links[] = '<a href="' . admin_url( 'admin.php?page=' . TWH_MENU_SLUG ) . '">' . __( 'Settings', 'trigger-webhooks' ) . '</a>';

		return $links;

	}
}
/**
 * Check if trigger_webhooks doesn't exist
 */
if ( ! function_exists( 'trigger_webhooks' ) ) {
	/**
	 * Load Trigger Webhooks
	 *
	 * @return TriggerWebhooks
	 */
	function trigger_webhooks() {
		return TriggerWebhooks::run();
	}
}
trigger_webhooks();
