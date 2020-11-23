<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2020 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoPlmPlace' ) ) {

	class WpssoPlmPlace {

		public static $place_mt = array(
			'plm_place_name'           => 'place:name',
			'plm_place_name_alt'       => 'place:name_alt',
			'plm_place_desc'           => 'place:description',
			'plm_place_street_address' => 'place:street_address',
			'plm_place_po_box_number'  => 'place:po_box_number',
			'plm_place_city'           => 'place:locality',
			'plm_place_region'         => 'place:region',
			'plm_place_postal_code'    => 'place:postal_code',
			'plm_place_country'        => 'place:country_name',
			'plm_place_phone'          => 'place:telephone',
		);

		public function __construct() {}

		public static function get_ids( $schema_type = '' ) {

			$place_names = self::get_names( $schema_type );

			return array_keys( $place_names );
		}

		/**
		 * Return an associative array of organization IDs and names.
		 *
		 * Optionally add 'none', 'new', and 'custom' at the top of the array.
		 */
		public static function get_names( $schema_type = '', $add_none = false, $add_new = false, $add_custom = false ) {

			$wpsso =& Wpsso::get_instance();

			if ( $wpsso->debug->enabled ) {

				$wpsso->debug->mark();
			}

			$first_names = array();
			$place_names = array();

			if ( $add_none ) {

				$first_names[ 'none' ] = $wpsso->cf[ 'form' ][ 'place_select' ][ 'none' ];
			}

			if ( $add_custom ) {

				$first_names[ 'custom' ] = $wpsso->cf[ 'form' ][ 'place_select' ][ 'custom' ];
			}

			if ( $wpsso->debug->enabled ) {

				$wpsso->debug->log( 'getting multi keys for plm_place_name' );
			}

			$place_names = SucomUtil::get_multi_key_locale( 'plm_place_name', $wpsso->options, false );	// $add_none is false.

			if ( ! empty( $schema_type ) && is_string( $schema_type) ) {

				if ( $wpsso->debug->enabled ) {

					$wpsso->debug->log( 'removing places not in schema type: ' . $schema_type );
				}

				$children = $wpsso->schema->get_schema_type_children( $schema_type );

				if ( ! empty( $children ) ) {	// Just in case.

					foreach ( $place_names as $place_id => $name ) {

						if ( ! empty( $wpsso->options[ 'plm_place_schema_type_' . $place_id ] ) && 
							in_array( $wpsso->options[ 'plm_place_schema_type_' . $place_id ], $children ) ) {

							continue;

						} else {

							unset( $place_names[ $place_id ] );
						}
					}
				}

			} elseif ( $wpsso->debug->enabled ) {

				$wpsso->debug->log( 'business type not provided - keeping all places' );
			}

			/**
			 * Add 'new' as the last place ID.
			 */
			if ( $add_new ) {

				$next_num = SucomUtil::get_next_num( $place_names );

				$place_names[ $next_num ] = $wpsso->cf[ 'form' ][ 'place_select' ][ 'new' ];
			}

			if ( ! empty( $first_names ) ) {

				$place_names = $first_names + $place_names;	// Combine arrays, preserving numeric key associations.
			}

			return $place_names;
		}

		/**
		 * Get a specific place id.
		 * 
		 * Returns an array of localized values.
		 *
		 * If $place_id is 'custom' then $mixed must be a $mod array.
		 *
		 * $place_id = 'custom' | place ID.
		 * $mixed    = 'default' | 'current' | post ID | $mod array.
		 */
		public static function get_id( $place_id, $mixed = 'current' ) {

			$wpsso =& Wpsso::get_instance();

			if ( $wpsso->debug->enabled ) {

				$wpsso->debug->log_args( array( 
					'place_id' => $place_id,
					'mixed'    => $mixed,
				) );
			}

			$place_opts = array();

			if ( '' === $place_id || 'none' === $place_id ) {	// Just in case.

				return false;
			}
			
			if ( 'custom' === $place_id ) {

				if ( ! isset( $mixed[ 'obj' ] ) || ! is_object( $mixed[ 'obj' ] ) ) {

					if ( $wpsso->debug->enabled ) {

						$wpsso->debug->log( 'exiting early: no module object defined' );
					}

					return false;
				}

				$md_opts = self::get_md_options( $mixed );	// Always returns and array.

				foreach ( SucomUtil::preg_grep_keys( '/^(plm_place_.*)(#.*)?$/', $md_opts, false, '$1' ) as $opt_key => $value ) {

					$place_opts[ $opt_key ] = SucomUtil::get_key_value( $opt_key, $md_opts, $mixed );
				}

				if ( empty( $place_opts ) ) {

					$place_opts = false;

				} else {

					$place_opts = array_merge( WpssoPlmConfig::$cf[ 'form' ][ 'plm_place_opts' ], $place_opts );	// Complete the array.

					$place_opts[ 'plm_place_id' ] = $place_id;
				}

				return $place_opts;

			}
			
			if ( is_numeric( $place_id ) ) {

				static $local_cache = array();	// Cache for single page load.

				$locale = get_locale( $mixed );

				if ( isset( $local_cache[ $place_id ][ $locale ] ) ) {

					if ( $wpsso->debug->enabled ) {

						$wpsso->debug->log( 'returning options from static cache array for place ID ' . $place_id );
					}

					return $local_cache[ $place_id ][ $locale ];
				}

				/**
				 * Get the list of non-localized option names.
				 */
				foreach ( SucomUtil::preg_grep_keys( '/^(plm_place_.*_)' . $place_id . '(#.*)?$/', $wpsso->options, false, '$1' ) as $opt_pre => $value ) {

					$opt_key = rtrim( $opt_pre, '_' );

					$place_opts[ $opt_key ] = SucomUtil::get_key_value( $opt_pre . $place_id, $wpsso->options, $mixed );
				}

				if ( empty( $place_opts ) ) {

					$place_opts = false;

				} else {

					$place_opts = array_merge( WpssoPlmConfig::$cf[ 'form' ][ 'plm_place_opts' ], $place_opts );	// Complete the array.

					$place_opts[ 'plm_place_id' ] = $place_id;
				}

				if ( $wpsso->debug->enabled ) {

					$wpsso->debug->log( 'saving options to static cache array for place ID ' . $place_id );
				}

				return $local_cache[ $place_id ][ $locale ] = $place_opts;
			}

			return false;
		}

		/**
		 * Return a text a value for the https://schema.org/address property.
		 */
		public static function get_address( array $place_opts ) {

			$wpsso =& Wpsso::get_instance();

			if ( $wpsso->debug->enabled ) {

				$wpsso->debug->mark();
			}

			$address = '';

			foreach ( array( 
				'plm_place_street_address',
				'plm_place_po_box_number',
				'plm_place_city',
				'plm_place_region',
				'plm_place_postal_code',
				'plm_place_country',
			) as $opt_key ) {

				if ( isset( $place_opts[ $opt_key ] ) && $place_opts[ $opt_key ] !== '' && $place_opts[ $opt_key ] !== 'none' ) {

					switch ( $opt_key ) {

						case 'plm_place_name':

							$place_opts[ $opt_key ] = preg_replace( '/\s*,\s*/', ' ', $place_opts[ $opt_key ] );	// Just in case.

							break;

						case 'plm_place_po_box_number':

							$address = rtrim( $address, ', ' ) . ' #';	// Continue street address.

							break;
					}

					$address .= $place_opts[ $opt_key ] . ', ';
				}
			}

			return rtrim( $address, ', ' );
		}

		/**
		 * Always returns an array.
		 */
		public static function get_md_options( array $mod ) {

			$wpsso =& Wpsso::get_instance();

			if ( $wpsso->debug->enabled ) {

				$wpsso->debug->mark();
			}

			if ( ! isset( $mod[ 'obj' ] ) || ! is_object( $mod[ 'obj' ] ) ) {	// Just in case.

				if ( $wpsso->debug->enabled ) {

					$wpsso->debug->log( 'exiting early: no module object defined' );
				}

				return array();
			}

			static $local_cache = array();	// Cache for single page load.

			if ( isset( $local_cache[ $mod[ 'name' ] ][ $mod[ 'id' ] ] ) ) {

				if ( $wpsso->debug->enabled ) {

					$wpsso->debug->log( 'returning options from static cache array for ' . $mod[ 'name' ] . ' ID ' . $mod[ 'id' ] );
				}

				return $local_cache[ $mod[ 'name' ] ][ $mod[ 'id' ] ];

			} else {

				if ( $wpsso->debug->enabled ) {

					$wpsso->debug->log( 'getting new options for static cache array for ' . $mod[ 'name' ] . ' ID ' . $mod[ 'id' ] );
				}

				$local_cache[ $mod[ 'name' ] ][ $mod[ 'id' ] ] = array();
			}

			$md_opts =& $local_cache[ $mod[ 'name' ] ][ $mod[ 'id' ] ];	// Shortcut variable name.

			$md_opts = $mod[ 'obj' ]->get_options( $mod[ 'id' ] );	// Returns empty string if no meta found.

			if ( is_array( $md_opts  ) ) {

				if ( isset( $md_opts[ 'plm_place_id' ] ) && is_numeric( $md_opts[ 'plm_place_id' ] ) ) {	// Allow for 0.

					if ( false !== ( $place_opts = self::get_id( $md_opts[ 'plm_place_id' ] ) ) ) {

						if ( $wpsso->debug->enabled ) {

							$wpsso->debug->log( 'using place id ' . $md_opts[ 'plm_place_id' ] . ' options' );
						}

						$md_opts = array_merge( $md_opts, $place_opts );
					}
				}

				$md_opts = SucomUtil::preg_grep_keys( '/^plm_/', $md_opts );	// Only return plm options.

				if ( ! empty( $md_opts ) ) {

					if ( $wpsso->debug->enabled ) {

						$wpsso->debug->log( count( $md_opts ) . ' plm option keys found' );
					}

					if ( empty( $md_opts[ 'plm_place_country' ] ) ) {

						$md_opts[ 'plm_place_country' ] = isset( $wpsso->options[ 'plm_def_country' ] ) ? $wpsso->options[ 'plm_def_country' ] : 'none';
					}

				} elseif ( $wpsso->debug->enabled ) {

					$wpsso->debug->log( 'no plm option keys found' );
				}
			}

			if ( $wpsso->debug->enabled ) {

				$wpsso->debug->log( 'result saved to static cache array for ' . $mod[ 'name' ] . ' ID ' . $mod[ 'id' ] );
			}

			return $md_opts;
		}

		public static function has_place( array $mod ) {

			$wpsso =& Wpsso::get_instance();

			if ( $wpsso->debug->enabled ) {

				$wpsso->debug->mark();
			}

			return self::has_md_place( $mod );	// Returns false or place array.
		}

		public static function has_md_place( array $mod ) {

			$wpsso =& Wpsso::get_instance();

			if ( $wpsso->debug->enabled ) {

				$wpsso->debug->mark();
			}

			if ( ! isset( $mod[ 'obj' ] ) || ! is_object( $mod[ 'obj' ] ) ) {	// Just in case.

				if ( $wpsso->debug->enabled ) {

					$wpsso->debug->log( 'exiting early: no module object defined' );
				}

				return false;
			}

			$md_opts = self::get_md_options( $mod );	// Always returns an array.

			$place_id = isset( $md_opts[ 'plm_place_id' ] ) ? $md_opts[ 'plm_place_id' ] : 'none';

			if ( '' == $place_id || 'none' === $place_id ) {	// Nothing to do.

				return false;
			}

			return $md_opts;
		}
	}
}
