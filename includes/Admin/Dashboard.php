<?php
/**
 * Dashboard
 *
 * @package TriggerWebhooks
 * @since 1.0.0
 */

namespace TWH\Admin;

use TWH\Helper;

/**
 * Dashboard Class
 *
 * @package TriggerWebhooks
 * @since 1.0.0
 */
class Dashboard {
	/**
	 * Menu
	 *
	 * @var string
	 */
	public static $menu = '';

	/**
	 * Menu Icon
	 *
	 * @var string
	 */
	public static $icon = TWH_ASSETS . 'images/sl.svg';

	/**
	 * Register
	 */
	public function register() {
		add_action( 'admin_menu', array( __CLASS__, 'register_menu' ) );

		if ( is_admin() && isset( $_GET[ 'page' ] ) && TWH_MENU_SLUG === wp_unslash( $_GET['page'] ) ) { // phpcs:ignore
			add_action( 'in_admin_header', array( $this, 'remove_notices' ) );
		}
	}


	/**
	 * Register Admin Menu
	 */
	public static function register_menu() {
		self::$menu = add_menu_page( __( 'Dashboard - Trigger Webhooks', 'trigger-webhooks' ), __( 'Trigger Webhooks', 'trigger-webhooks' ), 'manage_options', TWH_MENU_SLUG, array( __CLASS__, 'view_main' ), '', 56 );
	}

	/**
	 * Main View
	 */
	public static function view_main() {
		?>
		<div class="mh-popup mh-popup-wh-add">
			<div class="mh-popup__content">
				<button class="mh-popup__btn-close">X</button>
				<div class="mh-popup__content--form">
					<form action="" id="wh-form-add">
						<div class="mh-form-group">
							<label for="wh-a-url"><?php esc_html_e( 'Webhook URL', 'trigger-webhooks' ); ?></label>
							<input type="text" name="url" id="wh-a-url" placeholder="<?php esc_attr_e( 'Webhook URL', 'trigger-webhooks' ); ?>" required>
						</div>
						<div class="mh-form-group">
							<label for="wh-a-method"><?php esc_html_e( 'Method', 'trigger-webhooks' ); ?></label>
							<select name="method" id="wh-a-method">
								<option value="GET"><?php esc_html_e( 'GET', 'trigger-webhooks' ); ?></option>
								<option value="POST"><?php esc_html_e( 'POST', 'trigger-webhooks' ); ?></option>
							</select>
						</div>
						<div class="mh-form-group">
							<label for="wh-a-event-type"><?php esc_html_e( 'Event Type', 'trigger-webhooks' ); ?></label>
							<input type="text" name="event_type" id="wh-a-event-type" placeholder="<?php esc_attr_e( 'Event Type', 'trigger-webhooks' ); ?>" required>
							<span> user_register, save_post, profile_update, wp_insert_post </span>
						</div>
						<div class="mh-form-group">
							<label for="wh-a-payload"><?php esc_html_e( 'Payload', 'trigger-webhooks' ); ?></label>
							<textarea name="payload" id="wh-a-payload" placeholder="<?php esc_attr_e( 'Payload', 'trigger-webhooks' ); ?>" rows="7"></textarea>
						</div>
						<input type="hidden" name="wh-id" id="wh-id">
						<button type="button" id="wh_f_a">Add</button>
					</form>
				</div>
			</div>
		</div>
		<div class="mh-popup mh-popup-wh-update">
			<div class="mh-popup__content">
				<button class="mh-popup__btn-close">X</button>
				<div class="mh-popup__content--form">
					<form action="" id="wh-form">
						<div class="mh-form-group">
							<label for="wh-url"><?php esc_html_e( 'Webhook URL', 'trigger-webhooks' ); ?></label>
							<input type="text" name="url" id="wh-url" placeholder="<?php esc_attr_e( 'Webhook URL', 'trigger-webhooks' ); ?>" required>
						</div>
						<div class="mh-form-group">
							<label for="wh-method"><?php esc_html_e( 'Method', 'trigger-webhooks' ); ?></label>
							<select name="method" id="wh-method">
								<option value="GET"><?php esc_html_e( 'GET', 'trigger-webhooks' ); ?></option>
								<option value="POST"><?php esc_html_e( 'POST', 'trigger-webhooks' ); ?></option>
							</select>
						</div>
						<div class="mh-form-group">
							<label for="wh-event-type"><?php esc_html_e( 'Event Type', 'trigger-webhooks' ); ?></label>
							<input type="text" name="event_type" id="wh-event-type" placeholder="<?php esc_attr_e( 'Event Type', 'trigger-webhooks' ); ?>" required>
						</div>
						<div class="mh-form-group">
							<label for="wh-payload"><?php esc_html_e( 'Payload', 'trigger-webhooks' ); ?></label>
							<textarea name="payload" id="wh-payload" placeholder="<?php esc_attr_e( 'Payload', 'trigger-webhooks' ); ?>" rows="7"></textarea>
						</div>
						<input type="hidden" name="wh-id" id="wh-id">
						<button type="button" id="wh_f_u">Update</button>
					</form>
				</div>
			</div>
		</div>
		<div class="twh-data">
			<div class="twh-data__header">
				<h2><?php esc_html_e( 'Webhooks', 'trigger-webhooks' ); ?></h2>
				<button class="twh-data__btn-add"><?php esc_html_e( 'Add Webhook', 'trigger-webhooks' ); ?></button>
			</div>
			<table id="twh-table" width="100%">
				<thead>
					<tr role="row">
						<th>ID</th>
						<th>Webhook URL</th>
						<th>Method</th>
						<th>Event Type</th>
						<th>Payload</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
		<?php
	}

	/**
	 * Remove All Notices.
	 *
	 * @return void
	 */
	public function remove_notices() {
		remove_all_actions( 'admin_notices' );
		remove_all_actions( 'all_admin_notices' );
	}
}
