<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2019 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for...' );
}

if ( ! class_exists( 'WpssoPlmConfig' ) ) {

	class WpssoPlmConfig {

		public static $cf = array(
			'plugin' => array(
				'wpssoplm' => array(			// Plugin acronym.
					'version'     => '3.2.0-b.1',	// Plugin version.
					'opt_version' => '23',		// Increment when changing default option values.
					'short'       => 'WPSSO PLM',	// Short plugin name.
					'name'        => 'WPSSO Place / Location and Local Business Meta',
					'desc'        => 'WPSSO Core add-on provides Pinterest Place, Facebook / Open Graph Location, Schema Local Business and Local SEO meta tags.',
					'slug'        => 'wpsso-plm',
					'base'        => 'wpsso-plm/wpsso-plm.php',
					'update_auth' => 'tid',
					'text_domain' => 'wpsso-plm',
					'domain_path' => '/languages',
					'req'         => array(
						'short'       => 'WPSSO Core',
						'name'        => 'WPSSO Core',
						'min_version' => '4.31.2-b.1',
					),
					'assets' => array(
						'icons' => array(
							'low'  => 'images/icon-128x128.png',
							'high' => 'images/icon-256x256.png',
						),
					),
					'lib' => array(
						'gpl' => array(
							'admin' => array(
								'post' => 'Extend Post Settings',
							),
						),
						'pro' => array(
							'admin' => array(
								'post' => 'Extend Post Settings',
							),
						),
						'submenu' => array(
							'plm-general' => 'Place / Location',
						),
					),
				),
			),
			'opt' => array(
				'defaults' => array(
					'plugin_place_details_cache_exp' => DAY_IN_SECONDS,
					'plm_place_id'                   => 0,
					'plm_def_country'                => 'none',
					'plm_add_to_post'                => 0,
					'plm_add_to_page'                => 1,
					'plm_add_to_attachment'          => 0,
				),
			),
			/*
			'wp' => array(				// WordPress
				'transient' => array(
					'wpsso_p_' => array(
						'label'       => 'Place Details',
						'text_domain' => 'wpsso-plm',
						'opt_key'     => 'plugin_place_details_cache_exp',
						'filter'      => 'wpsso_cache_expire_place_details',
					),
				),
			),
			*/
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

			$ext  = 'wpssoplm';
			$info =& self::$cf[ 'plugin' ][$ext];

			return $add_slug ? $info[ 'slug' ] . '-' . $info[ 'version' ] : $info[ 'version' ];
		}

		public static function set_constants( $plugin_filepath ) { 

			if ( defined( 'WPSSOPLM_VERSION' ) ) {	// Define constants only once.
				return;
			}

			define( 'WPSSOPLM_FILEPATH', $plugin_filepath );						
			define( 'WPSSOPLM_PLUGINBASE', self::$cf[ 'plugin' ][ 'wpssoplm' ][ 'base' ] );	// wpsso-plm/wpsso-plm.php
			define( 'WPSSOPLM_PLUGINDIR', trailingslashit( realpath( dirname( $plugin_filepath ) ) ) );
			define( 'WPSSOPLM_PLUGINSLUG', self::$cf[ 'plugin' ][ 'wpssoplm' ][ 'slug' ] );	// wpsso-plm
			define( 'WPSSOPLM_URLPATH', trailingslashit( plugins_url( '', $plugin_filepath ) ) );
			define( 'WPSSOPLM_VERSION', self::$cf[ 'plugin' ][ 'wpssoplm' ][ 'version' ] );						
		}

		public static function require_libs( $plugin_filepath ) {

			require_once WPSSOPLM_PLUGINDIR . 'lib/filters.php';
			require_once WPSSOPLM_PLUGINDIR . 'lib/place.php';
			require_once WPSSOPLM_PLUGINDIR . 'lib/register.php';

			add_filter( 'wpssoplm_load_lib', array( 'WpssoPlmConfig', 'load_lib' ), 10, 3 );
		}

		public static function load_lib( $ret = false, $filespec = '', $classname = '' ) {

			if ( false === $ret && ! empty( $filespec ) ) {

				$filepath = WPSSOPLM_PLUGINDIR . 'lib/' . $filespec . '.php';

				if ( file_exists( $filepath ) ) {

					require_once $filepath;

					if ( empty( $classname ) ) {
						return SucomUtil::sanitize_classname( 'wpssoplm' . $filespec, $allow_underscore = false );
					} else {
						return $classname;
					}
				}
			}

			return $ret;
		}
	}
}

