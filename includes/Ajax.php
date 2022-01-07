<?php
/**
 * Class for handling Ajax
 *
 * @package TriggerWebhooks
 * @since 1.0.0
 */

namespace TWH;

use TWH\Helper;
use TWH\WebHooksDB;

/**
 * Manage All Ajax Request
 *
 * This class is for managing Ajax
 *
 * @package TriggerWebhooks
 * @since 1.0.0
 */
class Ajax {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'wp_ajax_twh_data', array( $this, 'twh_data' ) );
		add_action( 'wp_ajax_nopriv_twh_data', array( $this, 'twh_data' ) );
	}

	/**
	 * Save Blocks Options.
	 *
	 * @return void
	 */
	public function twh_data() {
		if ( ! isset( $_GET['nonce'] )
			|| ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['nonce'] ) ), 'twh-d-nonce' )
		) {
			echo 'Sorry, your nonce did not verify.';
			return;
		}

		$db   = new WebHooksDB();
		$data = $db->get_data();

		echo wp_json_encode( $data );

		wp_die();
	}
}
