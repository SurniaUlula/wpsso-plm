<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2012-2021 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoPlmScript' ) ) {

	class WpssoPlmScript {

		private $p;	// Wpsso class object.
		private $a;	// WpssoPlm class object.

		/**
		 * Instantiated by WpssoPlm->init_objects().
		 */
		public function __construct( &$plugin, &$addon ) {

			$this->p =& $plugin;
			$this->a =& $addon;

			$this->p->util->add_plugin_actions( $this, array(
				'admin_enqueue_scripts_editing_page' => 2,
			) );

			$this->p->util->add_plugin_filters( $this, array(
				'admin_page_script_data_option_labels' => 1,
			) );
		}

		public function action_admin_enqueue_scripts_editing_page( $hook_name, $file_ext ) {

			$version = WpssoPlmConfig::get_version();

			wp_register_script( 'plm-metabox',
				WPSSOPLM_URLPATH . 'js/jquery-plm-metabox.' . $file_ext,
					array( 'jquery', 'sucom-admin-page' ), $version, $in_footer = true );

			wp_enqueue_script( 'plm-metabox' );
		}

		public function filter_admin_page_script_data_option_labels( $option_labels ) {

			$option_labels[ 'plm_place_id' ] = _x( 'Select a Place', 'option label', 'wpsso-plm' );

			return $option_labels;
		}
	}
}
