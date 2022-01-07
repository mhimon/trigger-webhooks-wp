<?php
/**
 * Helper Class
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
class Helper {

	/**
	 * Constructor
	 */
	public function __construct() {
	}

	/**
	 * Add Option
	 *
	 * @param string $key Option Key.
	 * @param mixed  $value Option Value.
	 */
	public static function add_option( $key, $value ) {
		// Add Option.
		add_option( $key, $value );
	}

	/**
	 * Get Option
	 *
	 * @param string $key Option Key.
	 * @param mixed  $default Option Default.
	 */
	public static function get_option( $key, $default = false ) {
		// Get Option.
		$value = get_option( $key, $default );
		return $value;
	}

	/**
	 * Update Option
	 *
	 * @param string $key Option Key.
	 * @param mixed  $value Option Value.
	 */
	public static function update_option( $key, $value ) {
		// Update Option.
		update_option( $key, $value );
	}

	/**
	 * Delete Option
	 *
	 * @param string $key Option Key.
	 */
	public static function delete_option( $key ) {
		// Delete Option.
		delete_option( $key );
	}

	/**
	 * Multiple in_array
	 *
	 * @param array $needles needles.
	 * @param array $haystack haystack.
	 * @return boolean
	 */
	public function multiple_in_array( $needles, $haystack ) {
		return (bool) array_intersect( $needles, $haystack );
	}

	/**
	 * Time to Day(s) Converter
	 *
	 * @param int $time Time.
	 * @return int
	 */
	public static function time_to_days( $time ) {

		$current_time = current_time( 'timestamp' ); //phpcs:ignore
		return round( ( $current_time - $time ) / 24 / 60 / 60 );
	}

}
