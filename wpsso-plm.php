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
 * Description: Manage Schema Places and Local SEO for Facebook / Open Graph, Pinterest, and Google Local Business.
 * Requires PHP: 5.6
 * Requires At Least: 4.4
 * Tested Up To: 5.5.1
 * Version: 4.19.0
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

if ( ! class_exists( 'WpssoAddOn' ) ) {

	require_once dirname( __FILE__ ) . '/lib/abstracts/add-on.php';	// WpssoAddOn class.
}

if ( ! class_exists( 'WpssoPlm' ) ) {

	class WpssoPlm extends WpssoAddOn {

		public $filters;	// WpssoPlmFilters class.
		public $script;		// WpssoPlmScript class.

		protected $p;

		private static $instance = null;

		public function __construct() {

			parent::__construct( __FILE__, __CLASS__ );
		}

		public static function &get_instance() {

			if ( null === self::$instance ) {

				self::$instance = new self;
			}

			return self::$instance;
		}

		public function init_textdomain() {

			load_plugin_textdomain( 'wpsso-plm', false, 'wpsso-plm/languages/' );
		}

		public function init_objects( $is_admin, $doing_ajax, $doing_cron ) {

			$this->p =& Wpsso::get_instance();

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			if ( $this->get_missing_requirements() ) {	// Returns false or an array of missing requirements.

				return;	// Stop here.
			}

			$this->filters = new WpssoPlmFilters( $this->p );
			$this->script  = new WpssoPlmScript( $this->p );
		}
	}

        global $wpssoplm;

	$wpssoplm =& WpssoPlm::get_instance();
}
