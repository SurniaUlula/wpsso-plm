<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2020 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoPlmFiltersUpgrade' ) ) {

	class WpssoPlmFiltersUpgrade {

		private $p;

		/**
		 * Instantiated by WpssoPlmFilters->__construct().
		 */
		public function __construct( &$plugin ) {

			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			$this->p->util->add_plugin_filters( $this, array( 
				'rename_options_keys'                        => 1,
				'rename_md_options_keys'                     => 1,
			) );
		}

		public function filter_rename_options_keys( $options_keys ) {

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			$options_keys[ 'wpssoplm' ] = array(
				16 => array(
					'plm_addr_alt_name' => 'plm_place_name_alt',
				),
				21 => array(
					'plm_addr_delete'                   => '',
					'plm_addr_for_home'                 => '',
					'plm_addr_def_country'              => 'plm_def_country',
					'plm_addr_id'                       => 'plm_place_id',
					'plm_addr_name'                     => 'plm_place_name',
					'plm_addr_name_alt'                 => 'plm_place_name_alt',
					'plm_addr_desc'                     => 'plm_place_desc',
					'plm_addr_streetaddr'               => 'plm_place_street_address',
					'plm_addr_po_box_number'            => 'plm_place_po_box_number',
					'plm_addr_city'                     => 'plm_place_city',
					'plm_addr_state'                    => 'plm_place_state',
					'plm_addr_zipcode'                  => 'plm_place_zipcode',
					'plm_addr_country'                  => 'plm_place_country',
					'plm_addr_phone'                    => 'plm_place_phone',
					'plm_addr_latitude'                 => 'plm_place_latitude',
					'plm_addr_longitude'                => 'plm_place_longitude',
					'plm_addr_altitude'                 => 'plm_place_altitude',
					'plm_addr_schema_type'              => 'plm_place_schema_type',
					'plm_addr_business_type'            => 'plm_place_schema_type',
					'plm_addr_business_phone'           => '',
					'plm_addr_img_id'                   => 'plm_place_img_id',
					'plm_addr_img_id_pre'               => 'plm_place_img_id_pre',
					'plm_addr_img_url'                  => 'plm_place_img_url',
					'plm_addr_day_sunday'               => 'plm_place_day_sunday',
					'plm_addr_day_sunday_open'          => 'plm_place_day_sunday_open',
					'plm_addr_day_sunday_close'         => 'plm_place_day_sunday_close',
					'plm_addr_day_monday'               => 'plm_place_day_monday',
					'plm_addr_day_monday_open'          => 'plm_place_day_monday_open',
					'plm_addr_day_monday_close'         => 'plm_place_day_monday_close',
					'plm_addr_day_tuesday'              => 'plm_place_day_tuesday',
					'plm_addr_day_tuesday_open'         => 'plm_place_day_tuesday_open',
					'plm_addr_day_tuesday_close'        => 'plm_place_day_tuesday_close',
					'plm_addr_day_wednesday'            => 'plm_place_day_wednesday',
					'plm_addr_day_wednesday_open'       => 'plm_place_day_wednesday_open',
					'plm_addr_day_wednesday_close'      => 'plm_place_day_wednesday_close',
					'plm_addr_day_thursday'             => 'plm_place_day_thursday',
					'plm_addr_day_thursday_open'        => 'plm_place_day_thursday_open',
					'plm_addr_day_thursday_close'       => 'plm_place_day_thursday_close',
					'plm_addr_day_friday'               => 'plm_place_day_friday',
					'plm_addr_day_friday_open'          => 'plm_place_day_friday_open',
					'plm_addr_day_friday_close'         => 'plm_place_day_friday_close',
					'plm_addr_day_saturday'             => 'plm_place_day_saturday',
					'plm_addr_day_saturday_open'        => 'plm_place_day_saturday_open',
					'plm_addr_day_saturday_close'       => 'plm_place_day_saturday_close',
					'plm_addr_day_publicholidays'       => 'plm_place_day_publicholidays',
					'plm_addr_day_publicholidays_open'  => 'plm_place_day_publicholidays_open',
					'plm_addr_day_publicholidays_close' => 'plm_place_day_publicholidays_close',
					'plm_addr_season_from_date'         => 'plm_place_season_from_date',
					'plm_addr_season_to_date'           => 'plm_place_season_to_date',
					'plm_addr_service_radius'           => 'plm_place_service_radius',
					'plm_addr_currencies_accepted'      => 'plm_place_currencies_accepted',
					'plm_addr_payment_accepted'         => 'plm_place_payment_accepted',
					'plm_addr_price_range'              => 'plm_place_price_range',
					'plm_addr_accept_res'               => 'plm_place_accept_res',
					'plm_addr_menu_url'                 => 'plm_place_menu_url',
					'plm_addr_cuisine'                  => 'plm_place_cuisine',
					'plm_addr_order_urls'               => 'plm_place_order_urls',
				),
			);

			return $options_keys;
		}

		public function filter_rename_md_options_keys( $options_keys ) {

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			$options_keys[ 'wpssoplm' ] = array(
				8 => array(
					'plm_streetaddr'    => 'plm_place_street_address',
					'plm_po_box_number' => 'plm_place_po_box_number',
					'plm_city'          => 'plm_place_city',
					'plm_state'         => 'plm_place_state',
					'plm_zipcode'       => 'plm_place_zipcode',
					'plm_country'       => 'plm_place_country',
					'plm_latitude'      => 'plm_place_latitude',
					'plm_longitude'     => 'plm_place_longitude',
					'plm_altitude'      => 'plm_place_altitude',
				),
				16 => array(
					'plm_addr_alt_name' => 'plm_place_name_alt',
				),
				21 => array(
					'plm_addr_id'                       => 'plm_place_id',
					'plm_addr_name'                     => 'plm_place_name_alt',
					'plm_addr_name_alt'                 => 'plm_place_name_alt',
					'plm_addr_desc'                     => '',
					'plm_addr_streetaddr'               => 'plm_place_street_address',
					'plm_addr_po_box_number'            => 'plm_place_po_box_number',
					'plm_addr_city'                     => 'plm_place_city',
					'plm_addr_state'                    => 'plm_place_state',
					'plm_addr_zipcode'                  => 'plm_place_zipcode',
					'plm_addr_country'                  => 'plm_place_country',
					'plm_addr_phone'                    => 'plm_place_phone',
					'plm_addr_latitude'                 => 'plm_place_latitude',
					'plm_addr_longitude'                => 'plm_place_longitude',
					'plm_addr_altitude'                 => 'plm_place_altitude',
					'plm_addr_schema_type'              => 'plm_place_schema_type',
					'plm_addr_business_type'            => 'plm_place_schema_type',
					'plm_addr_business_phone'           => '',
					'plm_addr_img_id'                   => 'plm_place_img_id',
					'plm_addr_img_id_pre'               => 'plm_place_img_id_pre',
					'plm_addr_img_url'                  => 'plm_place_img_url',
					'plm_addr_day_sunday'               => 'plm_place_day_sunday',
					'plm_addr_day_sunday_open'          => 'plm_place_day_sunday_open',
					'plm_addr_day_sunday_close'         => 'plm_place_day_sunday_close',
					'plm_addr_day_monday'               => 'plm_place_day_monday',
					'plm_addr_day_monday_open'          => 'plm_place_day_monday_open',
					'plm_addr_day_monday_close'         => 'plm_place_day_monday_close',
					'plm_addr_day_tuesday'              => 'plm_place_day_tuesday',
					'plm_addr_day_tuesday_open'         => 'plm_place_day_tuesday_open',
					'plm_addr_day_tuesday_close'        => 'plm_place_day_tuesday_close',
					'plm_addr_day_wednesday'            => 'plm_place_day_wednesday',
					'plm_addr_day_wednesday_open'       => 'plm_place_day_wednesday_open',
					'plm_addr_day_wednesday_close'      => 'plm_place_day_wednesday_close',
					'plm_addr_day_thursday'             => 'plm_place_day_thursday',
					'plm_addr_day_thursday_open'        => 'plm_place_day_thursday_open',
					'plm_addr_day_thursday_close'       => 'plm_place_day_thursday_close',
					'plm_addr_day_friday'               => 'plm_place_day_friday',
					'plm_addr_day_friday_open'          => 'plm_place_day_friday_open',
					'plm_addr_day_friday_close'         => 'plm_place_day_friday_close',
					'plm_addr_day_saturday'             => 'plm_place_day_saturday',
					'plm_addr_day_saturday_open'        => 'plm_place_day_saturday_open',
					'plm_addr_day_saturday_close'       => 'plm_place_day_saturday_close',
					'plm_addr_day_publicholidays'       => 'plm_place_day_publicholidays',
					'plm_addr_day_publicholidays_open'  => 'plm_place_day_publicholidays_open',
					'plm_addr_day_publicholidays_close' => 'plm_place_day_publicholidays_close',
					'plm_addr_season_from_date'         => 'plm_place_season_from_date',
					'plm_addr_season_to_date'           => 'plm_place_season_to_date',
					'plm_addr_service_radius'           => 'plm_place_service_radius',
					'plm_addr_currencies_accepted'      => 'plm_place_currencies_accepted',
					'plm_addr_payment_accepted'         => 'plm_place_payment_accepted',
					'plm_addr_price_range'              => 'plm_place_price_range',
					'plm_addr_accept_res'               => 'plm_place_accept_res',
					'plm_addr_menu_url'                 => 'plm_place_menu_url',
					'plm_addr_cuisine'                  => 'plm_place_cuisine',
					'plm_addr_order_urls'               => 'plm_place_order_urls',
				),
			);

			return $options_keys;
		}
	}
}
