<?php
/**
 * WebHooks DB Class
 *
 * @package TriggerWebhooks
 * @since 1.0.0
 */

namespace TWH;

/**
 * Helper Class
 *
 * @package TriggerWebhooks
 * @since 1.0.0
 */
class WebHooksDB {

	/**
	 * The name of our database table
	 *
	 * @access  public
	 * @since   1.0
	 */
	public $table_name;

	/**
	 * The version of our database table
	 *
	 * @access  public
	 * @since   1.0
	 */
	public $version;

	/**
	 * The name of the primary column
	 *
	 * @access  public
	 * @since   1.0
	 */
	public $primary_key;

	/**
	 * Constructor
	 */
	public function __construct() {

		global $wpdb;

		$this->table_name  = $wpdb->prefix . 'webhooks';
		$this->primary_key = 'id';
		$this->version     = '1.0';
	}

	/**
	 * Get columns and formats
	 *
	 * @access  public
	 * @since   1.0
	*/
	public function get_columns() {
		return array(
			'id'         => '%d',
			'url'        => '%s',
			'method'     => '%s',
			'event_type' => '%s',
			'payload'    => '%s',
		);
	}

	/**
	 * Create Webhooks Table
	 *
	 * @return void
	 */
	public function create_table() {

		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$table_name      = $wpdb->prefix . 'webhooks';

		$sql = "CREATE TABLE $table_name (
			id int(9) NOT NULL AUTO_INCREMENT,
			url varchar(60) NOT NULL,
			method varchar(60) NOT NULL,
			event_type varchar(60) NOT NULL,
			payload text NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}

	/**
	 * Default column values
	 *
	 * @access  public
	 * @since   1.0
	 * @return  array
	 */
	public function get_column_defaults() {
		return array();
	}

	/**
	 * Get Data
	 *
	 * @return Object
	 */
	public function get_data() {

		global $wpdb;

		$results = $wpdb->get_results( "SELECT * FROM {$this->table_name}" );

		return $results;
	}

	/**
	 * Retrieve a row by the primary key
	 *
	 * @access  public
	 * @since   1.0
	 * @return  object
	 */
	public function get( $row_id ) {
		global $wpdb;
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $this->table_name WHERE $this->primary_key = %s LIMIT 1;", $row_id ) );
	}

	/**
	 * Insert a new row
	 *
	 * @access  public
	 * @since   1.0
	 * @return  int
	 */
	public function insert( $data ) {
		global $wpdb;

		// Set default values
		$data = wp_parse_args( $data, $this->get_column_defaults() );

		// Initialise column format array
		$column_formats = $this->get_columns();

		// Force fields to lower case
		$data = array_change_key_case( $data );

		// White list columns
		$data = array_intersect_key( $data, $column_formats );

		// Reorder $column_formats to match the order of columns given in $data
		$data_keys      = array_keys( $data );
		$column_formats = array_merge( array_flip( $data_keys ), $column_formats );

		$wpdb->insert( $this->table_name, $data, $column_formats );

		return $wpdb->insert_id;
	}

	/**
	 * Update a row
	 *
	 * @access  public
	 * @since   1.0
	 * @return  bool
	 */
	public function update( $row_id, $data = array(), $where = '' ) {

		global $wpdb;

		// Row ID must be positive integer
		$row_id = absint( $row_id );

		if ( empty( $row_id ) ) {
			return false;
		}

		if ( empty( $where ) ) {
			$where = $this->primary_key;
		}

		// Initialise column format array
		$column_formats = $this->get_columns();

		// Force fields to lower case
		$data = array_change_key_case( $data );

		// White list columns
		$data = array_intersect_key( $data, $column_formats );

		// Reorder $column_formats to match the order of columns given in $data
		$data_keys      = array_keys( $data );
		$column_formats = array_merge( array_flip( $data_keys ), $column_formats );

		$query = $wpdb->update( $this->table_name, $data, array( $where => $row_id ), $column_formats );

		if ( 0 === $query ) {
			return false;
		}

		return true;
	}

	/**
	 * Delete a row identified by the primary key
	 *
	 * @access  public
	 * @since   1.0
	 * @return  bool
	 */
	public function delete( $row_id = 0 ) {

		global $wpdb;

		// Row ID must be positive integer.
		$row_id = absint( $row_id );

		if ( empty( $row_id ) ) {
			return false;
		}

		$query = $wpdb->query( $wpdb->prepare( "DELETE FROM $this->table_name WHERE $this->primary_key = %d", $row_id ) );

		if ( 0 === $query ) {
			return false;
		}

		return true;
	}
}
