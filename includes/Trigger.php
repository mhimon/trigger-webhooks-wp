<?php
/**
 * Trigger WH
 *
 * @package TriggerWebhooks
 * @since 1.0.0
 */

namespace TWH;

use TWH\Helper;
use TWH\WebHooksDB;

/**
 * Trigger WH Class
 *
 * @package TriggerWebhooks
 * @since 1.0.0
 */
class Trigger {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->run_webhook();
	}

	/**
	 * Run Webhooks
	 *
	 * @return void
	 */
	public function run_webhook() {

		$webhook = new WebHooksDB();

		$webhook_data = $webhook->get_data();

		foreach ( $webhook_data as $webhook ) {

			$webhook_url        = $webhook->url;
			$webhook_method     = $webhook->method;
			$webhook_event_type = $webhook->event_type;
			$webhook_payload    = $webhook->payload;

			// $event = str_replace(
			// 	array( 'new_post', 'post_update' ),
			// 	array( 'wp_insert_post', 'save_post' ),
			// 	$webhook_event_type
			// );

			$this->trigger_event( $webhook_url, $webhook_method, $webhook_event_type, $webhook_payload );
		}
	}

	/**
	 * Undocumented function
	 *
	 * @param string $url URL.
	 * @param string $method Method.
	 * @param string $event_type Event Type.
	 * @param string $payload Payload.
	 * @return void
	 */
	public function trigger_event( $url, $method, $event_type, $payload ) {

		$args = array(
			'method'      => $method,
			'timeout'     => MINUTE_IN_SECONDS,
			'redirection' => 0,
			'httpversion' => '1.0',
			'blocking'    => false,
			'user-agent'  => sprintf( TWH_NAME . '/%s Trigger (WordPress/%s)', TWH_VERSION, $GLOBALS['wp_version'] ),
			'headers'     => array(
				'Content-Type' => 'application/json',
			),
			'cookies'     => array(),
			'body'        => trim( $payload ),
		);

		add_action(
			$event_type,
			function () use ( $url, $args ) {
				wp_safe_remote_request( $url, $args );
			}
		);
	}
}
