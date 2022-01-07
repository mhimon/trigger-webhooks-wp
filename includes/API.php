<?php
/**
 * Class for Rest API.
 *
 * @package TriggerWebhooks
 * @since 1.0.0
 */

namespace TWH;

use WP_REST_Server;
use TWH\Helper;
use TWH\WebHooksDB;
/**
 * Manage Rest API.
 *
 * This class is for managing Rest API.
 *
 * @package TriggerWebhooks
 * @since 1.0.0
 */
class API extends \WP_REST_Controller {

	/**
	 * Namespace
	 *
	 * @access  public
	 * @since   1.0
	 */
	public $namespace;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->namespace = 'twh/v1';
		add_action( 'rest_api_init', array( $this, 'register_rest_api' ) );
	}

	/**
	 * Register Rest API
	 */
	public function register_rest_api() {

		register_rest_route(
			$this->namespace,
			'/get_webhooks/',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_webhooks' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			$this->namespace,
			'/get_webhook/(?P<id>\d+)',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_webhook' ),
					'permission_callback' => '__return_true',
					'args'                => array(),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/add_webhook/',
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'insert_webhook' ),
					'permission_callback' => function ( $request ) {
						return current_user_can( 'manage_options' );
					},
					'args'                => array(),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/update_webhook/(?P<id>\d+)',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_webhook' ),
					'permission_callback' => function ( $request ) {
						return current_user_can( 'manage_options' );
					},
					'args'                => array(),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/delete_webhook/(?P<id>\d+)',
			array(
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_webhook' ),
					'permission_callback' => function ( $request ) {
						return current_user_can( 'manage_options' );
					},
					'args'                => array(),
				),
			)
		);

	}

	/**
	 * Get WebHooks
	 *
	 * @return array
	 */
	public function get_webhooks() {
		$db = new WebHooksDB();
		return $db->get_data();
	}

	/**
	 * Get WebHooks By ID
	 *
	 * @return array
	 */
	public function get_webhook( $request ) {
		$params = $request->get_params();
		$id     = (int) $params['id'];

		$db = new WebHooksDB();
		return $db->get( $id ) ? $db->get( $id ) : wp_send_json_error();
	}

	/**
	 * Add Web Hook
	 *
	 * @param object $req Request.
	 *
	 * @return array
	 */
	public function insert_webhook( $req ) {

		$params = $req->get_params();

		$db     = new WebHooksDB();
		$insert = $db->insert(
			$params
		);

		return $insert ? $insert : wp_send_json_error();
	}

	/**
	 * Update Web Hook
	 *
	 * @param object $req Request.
	 *
	 * @return array
	 */
	public function update_webhook( $req ) {

		$params = $req->get_params();
		$id     = (int) $params['id'];

		$db = new WebHooksDB();

		$update = $db->update(
			$id,
			$params
		);

		return $update ? $update : wp_send_json_error();
	}

	/**
	 * Delete Web Hook
	 *
	 * @param object $req Request.
	 *
	 * @return array
	 */
	public function delete_webhook( $req ) {

		$params = $req->get_params();
		$id     = (int) $params['id'];

		$db = new WebHooksDB();

		$delete = $db->delete( $id );

		return $delete ? $delete : wp_send_json_error();
	}

}
