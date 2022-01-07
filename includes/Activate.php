<?php
/**
 * Activate
 *
 * @package TriggerWebhooks
 * @since 1.0.0
 */

namespace TWH;

use TWH\Helper;
use TWH\WebHooksDB;

/**
 * Activate Class
 *
 * @package TriggerWebhooks
 * @since 1.0.0
 */
class Activate {
	/**
	 * The code that runs during plugin activation.
	 *
	 * @return void
	 */
	public function run() {
		$db = new WebHooksDB();
		$db->create_table();
		$this->plugin_data();
	}

	/**
	 * Save Plugin's Data
	 */
	public function plugin_data() {
		Helper::update_option( 'trigger_webhooks_version', TWH_VERSION );

		$installed_time = Helper::get_option( 'trigger_webhooks_installed_datetime', false );
		if ( ! $installed_time ) {
			Helper::update_option( 'trigger_webhooks_installed_datetime', current_time( 'timestamp' ) ); // phpcs:ignore
		}
	}


	/**
	 * Activation Redirect
	 */
	public function activation_redirect() {

		if ( get_option( 'trigger_webhooks_do_activation_redirect', false ) ) {

			delete_option( 'trigger_webhooks_do_activation_redirect' );
			wp_safe_redirect( admin_url( 'admin.php?page=' . TWH_MENU_SLUG ) );
			exit();
		}
	}
}
