<?php
/**
 * Database Background Process
 *
 * @package SmartToolz
 * @since 2.1.3
 */

if ( class_exists( 'SmartToolz_WP_Background_Process' ) ) {

	/**
	 * Database Background Process
	 *
	 * @since 2.1.3
	 */
	class SmartToolz_Theme_WP_Background_Process extends SmartToolz_WP_Background_Process {
		/**
		 * Database Process
		 *
		 * @var string
		 */
		protected $action = 'smarttoolz_theme_db_migration';

		/**
		 * Task
		 *
		 * Override this method to perform any actions required on each
		 * queue item. Return the modified item for further processing
		 * in the next pass through. Or, return false to remove the
		 * item from the queue.
		 *
		 * @since 2.1.3
		 *
		 * @param mixed $process Queue item to process.
		 * @return mixed
		 */
		protected function task( $process ) {

			if ( ! is_string( $process ) ) {
				return false;
			}

			// Only allow known update callback functions — prevents arbitrary function execution
			// if wp_options is ever written by a compromised source.
			$allowed_callbacks = array( 'update_db_version' );
			if ( class_exists( 'SmartToolz_Theme_Background_Updater' ) ) {
				$db_updates        = SmartToolz_Theme_Background_Updater::get_db_update_callbacks();
				$allowed_callbacks = array_merge( $allowed_callbacks, array_merge( ...array_values( $db_updates ) ) );
			}

			if ( ! in_array( $process, $allowed_callbacks, true ) ) {
				error_log( sprintf( 'SmartToolz: Blocked disallowed background process callback: %s', sanitize_text_field( $process ) ) );
				return false;
			}

			// Detach smarttoolz-settings option filters (e.g. WPML admin texts) so migrations read raw values and never persist translated strings back.
			$detached_option_filters = smarttoolz_detach_option_filters();

			do_action( 'smarttoolz_batch_process_task_' . $process, $process );

			if ( function_exists( $process ) ) {
				call_user_func( $process );
			}

			if ( 'update_db_version' === $process ) {
				SmartToolz_Theme_Background_Updater::update_db_version();
			}

			smarttoolz_restore_option_filters( $detached_option_filters );

			return false;
		}

		/**
		 * Complete
		 *
		 * Override if applicable, but ensure that the below actions are
		 * performed, or, call parent::complete().
		 *
		 * @since 2.1.3
		 */
		protected function complete() {
			do_action( 'smarttoolz_database_migration_complete' );
			parent::complete();
		}

	}

}
