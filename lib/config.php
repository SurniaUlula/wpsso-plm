<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2020 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoPlmConfig' ) ) {

	class WpssoPlmConfig {

		public static $cf = array(
			'plugin' => array(
				'wpssoplm' => array(			// Plugin acronym.
					'version'     => '4.17.0-dev.7',	// Plugin version.
					'opt_version' => '24',		// Increment when changing default option values.
					'short'       => 'WPSSO PLM',	// Short plugin name.
					'name'        => 'WPSSO Place and Local SEO Markup',
					'desc'        => 'Manage Places and Local SEO for Facebook / Open Graph, Pinterest, Google Local Business and Schema Place.',
					'slug'        => 'wpsso-plm',
					'base'        => 'wpsso-plm/wpsso-plm.php',
					'update_auth' => 'tid',
					'text_domain' => 'wpsso-plm',
					'domain_path' => '/languages',

					/**
					 * Required plugin and its version.
					 */
					'req' => array(
						'wpsso' => array(
							'name'          => 'WPSSO Core',
							'home'          => 'https://wordpress.org/plugins/wpsso/',
							'plugin_class'  => 'Wpsso',
							'version_const' => 'WPSSO_VERSION',
							'min_version'   => '7.16.0-dev.7',
						),
					),

					/**
					 * URLs or relative paths to plugin banners and icons.
					 */
					'assets' => array(

						/**
						 * Icon image array keys are '1x' and '2x'.
						 */
						'icons' => array(
							'1x' => 'images/icon-128x128.png',
							'2x' => 'images/icon-256x256.png',
						),
					),

					/**
					 * Library files loaded and instantiated by WPSSO.
					 */
					'lib' => array(
						'pro' => array(
							'admin' => array(
								'post-edit' => 'Post Edit Filters',
							),
						),
						'std' => array(
							'admin' => array(
								'post-edit' => 'Post Edit Filters',
							),
						),
						'submenu' => array(
							'plm-general' => 'Places',
						),
					),
				),
			),

			/**
			 * Additional add-on setting options.
			 */
			'opt' => array(
				'defaults' => array(
					'plugin_place_details_cache_exp' => DAY_IN_SECONDS,
					'plm_place_id'                   => 0,
					'plm_def_country'                => 'none',
					'plm_add_to_post'                => 0,
					'plm_add_to_page'                => 1,
					'plm_add_to_attachment'          => 0,
					
					/**
					 * Link itemprop.
					 */
					'add_link_itemprop_hasmenu' => 1,

					/**
					 * Meta itemprop.
					 */
					'add_meta_itemprop_address'             => 1,
					'add_meta_itemprop_telephone'           => 1,
					'add_meta_itemprop_currenciesaccepted'  => 1,
					'add_meta_itemprop_paymentaccepted'     => 1,
					'add_meta_itemprop_pricerange'          => 1,
					'add_meta_itemprop_acceptsreservations' => 1,
					'add_meta_itemprop_servescuisine'       => 1,
				),
			),
			'form' => array(
				'plm_place_opts' => array(
					'plm_place_schema_type'              => 'local.business',	// Place Schema Type
					'plm_place_name'                     => '',			// Place Name
					'plm_place_name_alt'                 => '',			// Place Altername Name
					'plm_place_desc'                     => '',			// Place Description
					'plm_place_street_address'           => '',			// Street Address
					'plm_place_po_box_number'            => '',			// P.O. Box Number
					'plm_place_city'                     => '',			// City
					'plm_place_state'                    => '',			// State / Province
					'plm_place_zipcode'                  => '',			// Zip / Postal Code
					'plm_place_country'                  => '',			// Country
					'plm_place_phone'                    => '',			// Telephone
					'plm_place_latitude'                 => '',			// Place Latitude
					'plm_place_longitude'                => '',			// Place Longitude
					'plm_place_altitude'                 => '',			// Place Altitude
					'plm_place_img_id'                   => '',			// Place Image ID
					'plm_place_img_id_pre'               => 'wp',
					'plm_place_img_url'                  => '',			// or Place Image URL
					'plm_place_day_sunday'               => 0,
					'plm_place_day_sunday_open'          => '09:00',
					'plm_place_day_sunday_close'         => '17:00',
					'plm_place_day_monday'               => 0,
					'plm_place_day_monday_open'          => '09:00',
					'plm_place_day_monday_close'         => '17:00',
					'plm_place_day_tuesday'              => 0,
					'plm_place_day_tuesday_open'         => '09:00',
					'plm_place_day_tuesday_close'        => '17:00',
					'plm_place_day_wednesday'            => 0,
					'plm_place_day_wednesday_open'       => '09:00',
					'plm_place_day_wednesday_close'      => '17:00',
					'plm_place_day_thursday'             => 0,
					'plm_place_day_thursday_open'        => '09:00',
					'plm_place_day_thursday_close'       => '17:00',
					'plm_place_day_friday'               => 0,
					'plm_place_day_friday_open'          => '09:00',
					'plm_place_day_friday_close'         => '17:00',
					'plm_place_day_saturday'             => 0,
					'plm_place_day_saturday_open'        => '09:00',
					'plm_place_day_saturday_close'       => '17:00',
					'plm_place_day_publicholidays'       => 0,
					'plm_place_day_publicholidays_open'  => '09:00',
					'plm_place_day_publicholidays_close' => '17:00',
					'plm_place_midday_close'             => '',		// Closes Mid-Day
					'plm_place_midday_open'              => '',
					'plm_place_season_from_date'         => '',		// Seasonal Dates
					'plm_place_season_to_date'           => '',
					'plm_place_service_radius'           => '',
					'plm_place_currencies_accepted'      => '',
					'plm_place_payment_accepted'         => '',
					'plm_place_price_range'              => '',
					'plm_place_accept_res'               => 0,
					'plm_place_menu_url'                 => '',
					'plm_place_cuisine'                  => '',
					'plm_place_order_urls'               => '',
				),
			),
		);

		public static function get_version( $add_slug = false ) {

			$info =& self::$cf[ 'plugin' ][ 'wpssoplm' ];

			return $add_slug ? $info[ 'slug' ] . '-' . $info[ 'version' ] : $info[ 'version' ];
		}

		public static function set_constants( $plugin_file_path ) { 

			if ( defined( 'WPSSOPLM_VERSION' ) ) {	// Define constants only once.
				return;
			}

			$info =& self::$cf[ 'plugin' ][ 'wpssoplm' ];

			/**
			 * Define fixed constants.
			 */
			define( 'WPSSOPLM_FILEPATH', $plugin_file_path );						
			define( 'WPSSOPLM_PLUGINBASE', $info[ 'base' ] );	// Example: wpsso-plm/wpsso-plm.php.
			define( 'WPSSOPLM_PLUGINDIR', trailingslashit( realpath( dirname( $plugin_file_path ) ) ) );
			define( 'WPSSOPLM_PLUGINSLUG', $info[ 'slug' ] );	// Example: wpsso-plm.
			define( 'WPSSOPLM_URLPATH', trailingslashit( plugins_url( '', $plugin_file_path ) ) );
			define( 'WPSSOPLM_VERSION', $info[ 'version' ] );						

			define( 'WPSSOPLM_CATEGORY_TAXONOMY', 'plm_category' );
			define( 'WPSSOPLM_PLACE_POST_TYPE', 'place' );

			/**
			 * Define variable constants.
			 */
			self::set_variable_constants();
		}

		public static function set_variable_constants( $var_const = null ) {

			if ( ! is_array( $var_const ) ) {
				$var_const = (array) self::get_variable_constants();
			}

			/**
			 * Define the variable constants, if not already defined.
			 */
			foreach ( $var_const as $name => $value ) {

				if ( ! defined( $name ) ) {
					define( $name, $value );
				}
			}
		}

		public static function get_variable_constants() { 

			$var_const = array();

			/**
			 * Maybe override the default constant value with a pre-defined constant value.
			 */
			foreach ( $var_const as $name => $value ) {

				if ( defined( $name ) ) {
					$var_const[$name] = constant( $name );
				}
			}

			return $var_const;
		}

		public static function require_libs( $plugin_file_path ) {

			require_once WPSSOPLM_PLUGINDIR . 'lib/filters.php';
			require_once WPSSOPLM_PLUGINDIR . 'lib/place.php';
			require_once WPSSOPLM_PLUGINDIR . 'lib/register.php';
			require_once WPSSOPLM_PLUGINDIR . 'lib/script.php';

			add_filter( 'wpssoplm_load_lib', array( 'WpssoPlmConfig', 'load_lib' ), 10, 3 );
		}

		public static function load_lib( $success = false, $filespec = '', $classname = '' ) {

			if ( false === $success && ! empty( $filespec ) ) {

				$file_path = WPSSOPLM_PLUGINDIR . 'lib/' . $filespec . '.php';

				if ( file_exists( $file_path ) ) {

					require_once $file_path;

					if ( empty( $classname ) ) {

						return SucomUtil::sanitize_classname( 'wpssoplm' . $filespec, $allow_underscore = false );
					}

					return $classname;
				}
			}

			return $success;
		}
	}
}
