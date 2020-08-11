<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2020 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoPlmFilters' ) ) {

	class WpssoPlmFilters {

		private $p;
		private $msgs;		// WpssoPlmFiltersMessages class object.
		private $upg;		// WpssoPlmFiltersUpgrade class object.

		public function __construct( &$plugin ) {

			/**
			 * Just in case - prevent filters from being hooked and executed more than once.
			 */
			static $do_once = null;

			if ( true === $do_once ) {

				return;	// Stop here.
			}

			$do_once = true;

			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			/**
			 * Instantiate the WpssoPlmFiltersUpgrade class object.
			 */
			if ( ! class_exists( 'WpssoPlmFiltersUpgrade' ) ) {

				require_once WPSSOPLM_PLUGINDIR . 'lib/filters-upgrade.php';
			}

			$this->upg = new WpssoPlmFiltersUpgrade( $plugin );

			$this->p->util->add_plugin_filters( $this, array( 
				'option_type'                                => 2,
				'save_setting_options'                       => 3,
				'save_post_options'                          => 4,
				'get_defaults'                               => 1,
				'get_md_defaults'                            => 1,
				'get_post_options'                           => 3,
				'og_type'                                    => 3,
				'og_seed'                                    => 2,
				'schema_meta_itemprop'                       => 4,
				'json_array_schema_type_ids'                 => 2,
				'json_prop_https_schema_org_potentialaction' => 5,
				'get_place_options'                          => 3,
			) );

			if ( is_admin() ) {

				/**
				 * Instantiate the WpssoPlmFiltersMessages class object.
				 */
				if ( ! class_exists( 'WpssoPlmFiltersMessages' ) ) {

					require_once WPSSOPLM_PLUGINDIR . 'lib/filters-messages.php';
				}

				$this->msgs = new WpssoPlmFiltersMessages( $plugin );

				$this->p->util->add_plugin_filters( $this, array( 
					'form_cache_place_names'  => 1,
					'post_document_meta_tabs' => 3,
				), $prio = 1000 );	// Run after WPSSO Core's own Standard / Premium filters.
			}
		}

		public function filter_option_type( $type, $base_key ) {

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			if ( ! empty( $type ) ) {

				return $type;

			} elseif ( strpos( $base_key, 'plm_' ) !== 0 ) {

				return $type;
			}

			switch ( $base_key ) {

				case 'plm_def_country':
				case 'plm_place_id':
				case 'plm_place_schema_type':		// Place Schema Type
				case ( preg_match( '/^plm_place_(country|type)$/', $base_key ) ? true : false ):

					return 'not_blank';

				case ( preg_match( '/^plm_place_(name|name_alt|desc|phone|street_address|city|state|zipcode)$/', $base_key ) ? true : false ):
				case ( preg_match( '/^plm_place_(phone|price_range|cuisine)$/', $base_key ) ? true : false ):

					return 'ok_blank';

				case ( preg_match( '/^plm_place_(currencies_accepted|payment_accepted)$/', $base_key ) ? true : false ):

					return 'csv_blank';

				case ( preg_match( '/^plm_place_(latitude|longitude|altitude|service_radius|po_box_number)$/', $base_key ) ? true : false ):

					return 'blank_num';

				case ( preg_match( '/^plm_place_day_[a-z]+_(open|close)$/', $base_key ) ? true : false ):

					return 'time';

				case ( preg_match( '/^plm_place_season_(from|to)_date$/', $base_key ) ? true : false ):

					return 'date';

				case 'plm_place_menu_url':

					return 'url';

				case 'plm_place_order_urls':

					return 'csv_urls';

				case 'plm_place_accept_res':
				case ( preg_match( '/^plm_place_day_[a-z]+$/', $base_key ) ? true : false ):

					return 'checkbox';
			}

			return $type;
		}

		/**
		 * $network is true if saving multisite network settings.
		 */
		public function filter_save_setting_options( array $opts, $network, $upgrading ) {

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			if ( $network ) {

				return $opts;	// Nothing to do.
			}

			$place_names = SucomUtil::get_multi_key_locale( 'plm_place_name', $opts, $add_none = false );

			$place_last_num = SucomUtil::get_last_num( $place_names );

			foreach ( $place_names as $place_id => $name ) {

				$name = trim( $name );

				/**
				 * Remove empty "New Place".
				 */
				if ( ! empty( $opts[ 'plm_place_delete_' . $place_id ] ) || ( $name === '' && $place_id === $place_last_num ) ) {

					/**
					 * Maybe reset the currently selected place ID.
					 */
					if ( isset( $opts[ 'plm_place_id' ] ) && $opts[ 'plm_place_id' ] === $place_id ) {

						unset( $opts[ 'plm_place_id' ] );
					}

					/**
					 * Remove the place, including all localized keys.
					 */
					$opts = SucomUtil::preg_grep_keys( '/^plm_place_.*_' . $place_id . '(#.*)?$/', $opts, $invert = true );

					continue;	// Check the next place.
				}

				/**
				 * Make sure each place has a name.
				 */
				if ( $name === '' ) {	// Just in case.
					$name = sprintf( _x( 'Place #%d', 'option value', 'wpsso-plm' ), $place_id );
				}

				$opts[ 'plm_place_name_' . $place_id ] = $name;

				if ( ! empty( $opts[ 'plm_place_img_id_' . $place_id ] ) ) {	// Image id 0 is not valid.

					/**
					 * Remove the image url options if we have an image id.
					 */
					unset(
						$opts[ 'plm_place_img_url_' . $place_id ],
						$opts[ 'plm_place_img_url:width_' . $place_id ],
						$opts[ 'plm_place_img_url:height_' . $place_id ]
					);

					/**
					 * Get the location image and issue an error if the original image is too small.
					 *
					 * Only check on a manual save, not an options upgrade action (ie. when a new add-on is activated).
					 */
					if ( ! $upgrading ) {

						$this->check_location_image_sizes( $opts, $place_id );
					}
				}
			}

			$opts = SucomUtil::preg_grep_keys( '/^plm_place_delete_/', $opts, $invert = true );	// Just in case.

			return $opts;
		}

		public function filter_save_post_options( $md_opts, $post_id, $rel_id, $mod ) {

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			$this->update_post_md_opts( $md_opts, $post_id, $mod );	// Modifies the $md_opts array.

			return $md_opts;
		}

		public function filter_get_defaults( $defs ) {

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			/**
			 * Add options using a key prefix array and post type names.
			 */
			$this->p->util->add_post_type_names( $defs, array(
				'plm_add_to' => 1,
			) );

			return $defs;
		}

		public function filter_get_md_defaults( $md_defs ) {

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			$md_defs = array_merge(
				$md_defs,
				WpssoPlmConfig::$cf[ 'form' ][ 'plm_place_opts' ],
				array(
					'plm_place_id'      => 'none',
					'plm_place_country' => $this->p->options[ 'plm_def_country' ],
				)
			);

			return $md_defs;
		}

		public function filter_get_post_options( array $md_opts, $post_id, array $mod ) {
			
			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			$this->update_post_md_opts( $md_opts, $post_id, $mod );	// Modifies the $md_opts array.

			return $md_opts;
		}

		public function filter_og_type( $og_type, $mod, $is_custom ) {

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			if ( $is_custom ) {

				if ( $this->p->debug->enabled ) {

					$this->p->debug->log( 'exiting early: custom og type is true' );
				}

				return $og_type;
			}

			if ( $og_type === 'place' ) {	// Just in case.

				if ( $this->p->debug->enabled ) {

					$this->p->debug->log( 'exiting early: og type is already "place"' );
				}

				return $og_type;
			}

			$place_opts = WpssoPlmPlace::has_place( $mod );	// Returns false or place array.

			if ( empty( $place_opts ) ) {

				if ( $this->p->debug->enabled ) {

					$this->p->debug->log( 'exiting early: no place options found' );
				}

				return $og_type;	// Stop here.
			}

			if ( $this->p->debug->enabled ) {

				$this->p->debug->log( 'returning og_type "place"' );
			}

			return 'place';
		}

		public function filter_og_seed( array $mt_og, array $mod ) {

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			$place_opts = WpssoPlmPlace::has_place( $mod );	// Returns false or place array.

			if ( empty( $place_opts ) ) {

				if ( $this->p->debug->enabled ) {

					$this->p->debug->log( 'exiting early: no place options found' );
				}

				return $mt_og;     // Stop here.
			}

			if ( $this->p->debug->enabled ) {

				$this->p->debug->log( 'returning meta tags for "place"' );
			}

			/**
			 * og:type
			 */
			$mt_og[ 'og:type' ] = 'place';	// Pre-define to optimize.

			/**
			 * place:name
			 * place:street_address
			 * place:po_box_number
			 * place:locality
			 * place:region
			 * place:postal_code
			 * place:country_name
			 * place:telephone
			 */
			foreach ( WpssoPlmPlace::$place_mt as $key => $mt_name ) {
				$mt_og[ $mt_name ] = isset( $place_opts[ $key ] ) && $place_opts[ $key ] !== 'none' ? $place_opts[ $key ] : '';
			}

			/**
			 * og:latitude
			 * og:longitude
			 * og:altitude
			 * place:location:latitude
			 * place:location:longitude
			 * place:location:altitude
			 */
			if ( isset( $place_opts[ 'plm_place_latitude' ] ) && $place_opts[ 'plm_place_latitude' ] !== '' && 
				isset( $place_opts[ 'plm_place_longitude' ] ) && $place_opts[ 'plm_place_longitude' ] !== '' ) {

				foreach( array( 'place:location', 'og' ) as $mt_pre ) {

					$mt_og[ $mt_pre . ':latitude' ]  = $place_opts[ 'plm_place_latitude' ];
					$mt_og[ $mt_pre . ':longitude' ] = $place_opts[ 'plm_place_longitude' ];

					if ( ! empty( $place_opts[ 'plm_altitude' ] ) ) {

						$mt_og[ $mt_pre . ':altitude' ] = $place_opts[ 'plm_place_altitude' ];
					}
				}
			}

			/**
			 * Non-standard meta tags for internal use.
			 */
			$place_defs = WpssoPlmConfig::$cf[ 'form' ][ 'plm_place_opts' ];

			foreach ( $this->p->cf[ 'form' ][ 'weekdays' ] as $weekday => $label ) {

				if ( ! empty( $place_opts[ 'plm_place_day_' . $weekday ] ) ) {

					foreach ( array( 'open', 'close' ) as $hour ) {

						$mt_key  = 'place:opening_hours:day:' . $weekday . ':' . $hour;
						$opt_key = 'plm_place_day_' . $weekday . '_' . $hour;

						$mt_og[ $mt_key ] = isset( $place_opts[ $opt_key ] ) ? $place_opts[ $opt_key ] : $place_defs[ $opt_key ];
					}
				}
			}

			foreach ( array(
				'plm_place_midday_close'        => 'place:opening_hours:midday:close',
				'plm_place_midday_open'         => 'place:opening_hours:midday:open',
				'plm_place_season_from_date'    => 'place:opening_hours:season:from_date',
				'plm_place_season_to_date'      => 'place:opening_hours:season:to_date',
				'plm_place_service_radius'      => 'place:business:service_radius',
				'plm_place_currencies_accepted' => 'place:business:currencies_accepted',
				'plm_place_payment_accepted'    => 'place:business:payment_accepted',
				'plm_place_price_range'         => 'place:business:price_range',
				'plm_place_accept_res'          => 'place:business:accepts_reservations',
				'plm_place_menu_url'            => 'place:business:menu_url',
				'plm_place_order_urls'          => 'place:business:order_url',
			) as $opt_key => $mt_name ) {

				if ( isset( $place_opts[ $opt_key ] ) ) {

					if ( $opt_key === 'plm_place_accept_res' ) {

						$mt_og[ $mt_name ] = empty( $place_opts[ $opt_key ] ) ? false : true;

					} elseif ( $opt_key === 'plm_place_order_urls' ) {

						$mt_og[ $mt_name ] = SucomUtil::explode_csv( $place_opts[ $opt_key ] );

					} else {
						$mt_og[ $mt_name ] = $place_opts[ $opt_key ];
					}

				} else {
					$mt_og[ $mt_name ] = '';
				}
			}

			return $mt_og;
		}

		public function filter_schema_meta_itemprop( $mt_schema, $mod, $mt_og, $page_type_id ) {

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			$place_opts = WpssoPlmPlace::has_place( $mod );	// Returns false or place array.

			if ( empty( $place_opts ) ) {

				if ( $this->p->debug->enabled ) {

					$this->p->debug->log( 'exiting early: no place options found' );
				}

				return $mt_schema;	// Stop here.
			}

			/**
			 * Place properties.
			 */
			$mt_schema[ 'address' ] = WpssoPlmPlace::get_address( $place_opts );

			foreach ( array(
				'plm_place_phone' => 'telephone',	// Place phone number.
			) as $opt_key => $mt_name ) {

				$mt_schema[ $mt_name ] = isset( $place_opts[ $opt_key ] ) ? $place_opts[ $opt_key ] : '';
			}

			/**
			 * Local business properties.
			 */
			if ( $this->p->schema->is_schema_type_child( $page_type_id, 'local.business' ) ) {

				if ( $this->p->debug->enabled ) {

					$this->p->debug->log( 'schema type is child of local.business' );
				}

				foreach ( array(
					'plm_place_currencies_accepted' => 'currenciesaccepted',
					'plm_place_payment_accepted'    => 'paymentaccepted',
					'plm_place_price_range'         => 'pricerange',
				) as $opt_key => $mt_name ) {

					$mt_schema[ $mt_name ] = isset( $place_opts[ $opt_key ] ) ? $place_opts[ $opt_key ] : '';
				}

			} elseif ( $this->p->debug->enabled ) {

				$this->p->debug->log( 'schema type is not a child of local.business' );
			}

			/**
			 * Food establishment properties.
			 */
			if ( $this->p->schema->is_schema_type_child( $page_type_id, 'food.establishment' ) ) {

				if ( $this->p->debug->enabled ) {

					$this->p->debug->log( 'schema type is child of food.establishment' );
				}

				foreach ( array(
					'plm_place_accept_res' => 'acceptsreservations',
					'plm_place_menu_url'   => 'hasmenu',
					'plm_place_cuisine'    => 'servescuisine',
				) as $opt_key => $mt_name ) {

					if ( $opt_key === 'plm_place_accept_res' ) {

						$mt_schema[ $mt_name ] = empty( $place_opts[ $opt_key ] ) ? 'false' : 'true';

					} else {

						$mt_schema[ $mt_name ] = isset( $place_opts[ $opt_key ] ) ? $place_opts[ $opt_key ] : '';
					}
				}
			}

			return $mt_schema;
		}

		public function filter_json_array_schema_type_ids( $type_ids, $mod ) {

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			$place_opts = WpssoPlmPlace::has_place( $mod );	// Returns false or place array.

			if ( empty( $place_opts ) ) {

				if ( $this->p->debug->enabled ) {

					$this->p->debug->log( 'exiting early: no place options found' );
				}

				return $type_ids;	// Stop here.
			}

			/**
			 * Example $place_ids array:
			 *
			 * Array (
			 *	[website]        => 1
			 *	[organization]   => 1
			 *	[person]         => 1
			 *	[local.business] => 1
			 * )
			 */
			$def_schema_type = WpssoPlmConfig::$cf[ 'form' ][ 'plm_place_opts' ][ 'plm_place_schema_type' ];

			$type_id = empty( $place_opts[ 'plm_place_schema_type' ] ) ? $def_schema_type : $place_opts[ 'plm_place_schema_type' ];

			if ( $this->p->debug->enabled ) {

				$this->p->debug->log( 'adding schema type id "' . $type_id . '"' );
			}

			$type_ids[ $type_id ] = true;

			return $type_ids;
		}

		public function filter_json_prop_https_schema_org_potentialaction( $action_data, $mod, $mt_og, $page_type_id, $is_main ) {

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			if ( ! $is_main ) {

				return $action_data;
			}

			if ( empty( $mt_og[ 'place:business:order_url' ] ) ) {

				return $action_data;
			}

			$action_data[] = array(
				'@context' => 'https://schema.org',
				'@type'    => 'OrderAction',
				'target'   => $mt_og[ 'place:business:order_url' ],
			);

			return $action_data;
		}

		public function filter_get_place_options( $place_opts, $mod, $place_id ) {

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			if ( false !== $place_opts ) {	// First come, first served.

				if ( $this->p->debug->enabled ) {

					$this->p->debug->log( 'exiting early: place_opts array already defined' );
				}

				return $place_opts;
			}

			/**
			 * $place_id can be 'none', 'custom', or a place ID number.
			 */
			if ( $place_id === 'custom' || is_numeric( $place_id ) ) {	// Allow for place ID 0.

				$plm_place_opts = WpssoPlmPlace::get_id( $place_id, $mod );

				if ( is_array( $plm_place_opts ) ) {	// Just in case.

					return SucomUtil::preg_grep_keys( '/^plm_place_/', $plm_place_opts, false, 'place_' );	// Rename plm_place to place.
				}
			}

			return $place_opts;
		}

		/**
		 * Add our place names to the place cache array.
		 */
		public function filter_form_cache_place_names( $mixed ) {

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			$ret = WpssoPlmPlace::get_names();

			if ( is_array( $mixed ) ) {

				$ret = $mixed + $ret;
			}

			return $ret;
		}

		public function filter_post_document_meta_tabs( $tabs, $mod, $metabox_id ) {

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			if ( $metabox_id === $this->p->cf[ 'meta' ][ 'id' ] ) {

				if ( ! empty( $this->p->options[ 'plm_add_to_' . $mod[ 'post_type' ]] ) ) {

					SucomUtil::add_after_key( $tabs, 'media', 'place', _x( 'Schema Place', 'metabox tab', 'wpsso-plm' ) );
				}
			}

			return $tabs;
		}

		private function update_post_md_opts( &$md_opts, $post_id, $mod ) {

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			$place_id = isset( $md_opts[ 'plm_place_id' ] ) ? $md_opts[ 'plm_place_id' ] : 'none';

			$place_type = false;

			$def_schema_type = WpssoPlmConfig::$cf[ 'form' ][ 'plm_place_opts' ][ 'plm_place_schema_type' ];

			if ( $place_id === '' || $place_id === 'none' ) {	// Nothing to do.

				$md_opts = SucomUtil::preg_grep_keys( '/^plm_place_/', $md_opts, true );

				return $md_opts;

			} elseif ( $place_id === 'custom' ) {	// Value is "custom".

				$place_type = empty( $md_opts[ 'plm_place_schema_type' ] ) ? $def_schema_type : $md_opts[ 'plm_place_schema_type' ];

			} elseif ( is_numeric( $place_id ) ) {	// Value is "0" or more.

				$place_opts = WpssoPlmPlace::get_id( $place_id, $mod );

				$place_type = empty( $place_opts[ 'plm_place_schema_type' ] ) ? $def_schema_type : $place_opts[ 'plm_place_schema_type' ];
			}

			$md_opts[ 'og_type' ] = 'place';	// Overwrite the WPSSO option value.

			$md_opts[ 'og_type:is' ] = 'disabled';

			if ( $place_type ) {

				$md_opts[ 'schema_type' ] = $place_type;

				$md_opts[ 'schema_type:is' ] = 'disabled';

				$md_opts[ 'schema_organization_org_id' ] = 'none';

				$md_opts[ 'schema_organization_org_id:is' ] = 'disabled';
			}

			return $md_opts;
		}

		/**
		 * Get the location image and issue an error if the original image is too small.
		 */
		private function check_location_image_sizes( $opts, $opt_num = null ) {

			$name_transl = SucomUtil::get_key_value( 'plm_place_name_' . $opt_num, $opts, 'current' );

			$context_transl = sprintf( __( 'saving place "%1$s"', 'wpsso-plm' ), $name_transl );

			$settings_page_link = $this->p->util->get_admin_url( 'plm-general' );

			$this->p->notice->set_ref( $settings_page_link, false, $context_transl );

			$mt_images = $wpsso->media->get_mt_opts_images( $opts, $size_names = 'schema', $img_pre = 'plm_place_img', $opt_num );

			$this->p->notice->unset_ref( $settings_page_link );
		}
	}
}
