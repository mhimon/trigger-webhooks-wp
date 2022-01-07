<?php
/**
 * Assets Manager Class
 *
 * @package TriggerWebhooks
 * @since 1.0.0
 */

namespace TWH;

use TWH\Admin\Dashboard;
use TWH\Helper;

/**
 * Manage All Assets
 *
 * This class is for managing Assets
 *
 * @package TriggerWebhooks
 * @since 1.0.0
 */
class Assets_Manager {

	/**
	 * Admin Assets
	 *
	 * Enqueue Admin Styles and Scripts
	 *
	 * @param string $hook Page slug.
	 */
	public function admin_assets( $hook ) {

		if ( 'toplevel_page_' . TWH_MENU_SLUG !== $hook || ! current_user_can( 'manage_options' ) ) {
			return;
		}

		wp_enqueue_style( 'datatables', 'https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.css', '', TWH_VERSION );
		wp_enqueue_script( 'datatables', 'https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.js', array( 'jquery' ), TWH_VERSION, true );

		wp_enqueue_style( 'twh-admin', TWH_ASSETS . 'css/admin.css', '', TWH_VERSION );
		wp_enqueue_script( 'twh-admin', TWH_ASSETS . 'js/admin.js', array( 'wp-api-fetch' ), TWH_VERSION, true );

		wp_localize_script(
			'twh-admin',
			'TWHAdmin',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'twh-d-nonce' ),
				'version' => TWH_VERSION,
				'assets'  => TWH_ASSETS,
			)
		);
	}

}
