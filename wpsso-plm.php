<?php
/**
 * Plugin Name: WPSSO Place and Local SEO Markup
 * Plugin Slug: wpsso-plm
 * Text Domain: wpsso-plm
 * Domain Path: /languages
 * Plugin URI: https://wpsso.com/extend/plugins/wpsso-plm/
 * Assets URI: https://surniaulula.github.io/wpsso-plm/assets/
 * Author: JS Morisset
 * Author URI: https://wpsso.com/
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Description: Manage Places and Local SEO for Facebook / Open Graph, Pinterest, Google Local Business and Schema Place.
 * Requires PHP: 5.6
 * Requires At Least: 4.0
 * Tested Up To: 5.4
 * Version: 4.9.0-dev.2
 * 
 * Version Numbering: {major}.{minor}.{bugfix}[-{stage}.{level}]
 *
 *      {major}         Major structural code changes / re-writes or incompatible API changes.
 *      {minor}         New functionality was added or improved in a backwards-compatible manner.
 *      {bugfix}        Backwards-compatible bug fixes or small improvements.
 *      {stage}.{level} Pre-production release: dev < a (alpha) < b (beta) < rc (release candidate).
 * 
 * Copyright 2014-2020 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoPlm' ) ) {

	class WpssoPlm {

		/**
		 * Wpsso plugin class object variable.
		 */
		public $p;		// Wpsso

		/**
		 * Library class object variables.
		 */
		public $filters;	// WpssoPlmFilters
		public $reg;		// WpssoPlmRegister
		public $script;		// WpssoPlmScript

		/**
		 * Reference Variables (config, options, modules, etc.).
		 */
		private $have_min_version = true;	// Have minimum wpsso version.

		private static $instance;

		public function __construct() {

			require_once dirname( __FILE__ ) . '/lib/config.php';

			WpssoPlmConfig::set_constants( __FILE__ );

			WpssoPlmConfig::require_libs( __FILE__ );	// Includes the register.php class library.

			$this->reg = new WpssoPlmRegister();		// Activate, deactivate, uninstall hooks.

			if ( is_admin() ) {
				add_action( 'admin_init', array( __CLASS__, 'required_check' ) );
			}

			/**
			 * Add WPSSO filter hooks.
			 */
			add_filter( 'wpsso_get_config', array( $this, 'wpsso_get_config' ), 20, 2 );	// Checks core version and merges config array.

			/**
			 * Add WPSSO action hooks.
			 */
			add_action( 'wpsso_init_textdomain', array( __CLASS__, 'wpsso_init_textdomain' ) );
			add_action( 'wpsso_init_options', array( $this, 'wpsso_init_options' ), 20 );	// Sets the $this->p reference variable.
			add_action( 'wpsso_init_objects', array( $this, 'wpsso_init_objects' ), 20 );
			add_action( 'wpsso_init_plugin', array( $this, 'wpsso_init_plugin' ), 20 );
		}

		public static function &get_instance() {

			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		public static function required_check() {

			if ( ! class_exists( 'Wpsso' ) ) {
				add_action( 'all_admin_notices', array( __CLASS__, 'required_notice' ) );
			}
		}

		public static function required_notice() {

			self::wpsso_init_textdomain();

			$info = WpssoPlmConfig::$cf[ 'plugin' ][ 'wpssoplm' ];

			$error_msg = __( 'The %1$s add-on requires the %2$s plugin &mdash; install and activate the %3$s plugin or <a href="%4$s">deactivate the %5$s add-on</a>.', 'wpsso-plm' );

			$deactivate_url = html_entity_decode( wp_nonce_url( add_query_arg( array(
				'action'        => 'deactivate',
				'plugin'        => $info[ 'base' ],
				'plugin_status' => 'all',
				'paged'         => 1,
				's'             => '',
			), admin_url( 'plugins.php' ) ), 'deactivate-plugin_' . $info[ 'base' ] ) );

			echo '<div class="notice notice-error error"><p>';
			echo sprintf( $error_msg, $info[ 'name' ], $info[ 'req' ][ 'name' ], $info[ 'req' ][ 'short' ], $deactivate_url, $info[ 'short' ] );
			echo '</p></div>';
		}

		public static function wpsso_init_textdomain() {

			load_plugin_textdomain( 'wpsso-plm', false, 'wpsso-plm/languages/' );
		}

		/**
		 * Checks the core plugin version and merges the extension / add-on config array.
		 */
		public function wpsso_get_config( $cf, $plugin_version = 0 ) {

			$info = WpssoPlmConfig::$cf[ 'plugin' ][ 'wpssoplm' ];

			if ( version_compare( $plugin_version, $info[ 'req' ][ 'min_version' ], '<' ) ) {

				$this->have_min_version = false;

				return $cf;
			}

			return SucomUtil::array_merge_recursive_distinct( $cf, WpssoPlmConfig::$cf );
		}

		/**
		 * Sets the $this->p reference variable for the core plugin instance.
		 */
		public function wpsso_init_options() {

			$this->p =& Wpsso::get_instance();

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			if ( ! $this->have_min_version ) {

				$this->p->avail[ 'p_ext' ][ 'plm' ] = false;	// Signal that this extension / add-on is not available.

				return;
			}

			$this->p->avail[ 'p_ext' ][ 'plm' ] = true;		// Signal that this extension / add-on is available.
		}

		public function wpsso_init_objects() {

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			if ( ! $this->have_min_version ) {
				return;	// Stop here.
			}

			$this->filters = new WpssoPlmFilters( $this->p );
			$this->script  = new WpssoPlmScript( $this->p );
		}

		/**
		 * All WPSSO objects are instantiated and configured.
		 */
		public function wpsso_init_plugin() {

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			if ( ! $this->have_min_version ) {

				$this->min_version_notice();	// Show minimum version notice.

				return;	// Stop here.
			}
		}

		private function min_version_notice() {

			if ( ! is_admin() ) {
				return;
			}

			$info = WpssoPlmConfig::$cf[ 'plugin' ][ 'wpssoplm' ];

			$error_msg = sprintf( __( 'The %1$s version %2$s add-on requires %3$s version %4$s or newer (version %5$s is currently installed).',
				'wpsso-plm' ), $info[ 'name' ], $info[ 'version' ], $info[ 'req' ][ 'short' ], $info[ 'req' ][ 'min_version' ],
					$this->p->cf[ 'plugin' ][ 'wpsso' ][ 'version' ] );

			$this->p->notice->err( $error_msg );

			if ( method_exists( $this->p->admin, 'get_check_for_updates_link' ) ) {

				$update_msg = $this->p->admin->get_check_for_updates_link();

				if ( ! empty( $update_msg ) ) {
					$this->p->notice->inf( $update_msg );
				}
			}
		}
	}

        global $wpssoplm;

	$wpssoplm =& WpssoPlm::get_instance();
}
