<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2020 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoPlmSubmenuPlmGeneral' ) && class_exists( 'WpssoAdmin' ) ) {

	class WpssoPlmSubmenuPlmGeneral extends WpssoAdmin {

		public function __construct( &$plugin, $id, $name, $lib, $ext ) {

			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			$this->menu_id   = $id;
			$this->menu_name = $name;
			$this->menu_lib  = $lib;
			$this->menu_ext  = $ext;
		}

		/**
		 * Called by the extended WpssoAdmin class.
		 */
		protected function add_meta_boxes() {

			$this->maybe_show_language_notice();

			$metabox_id      = 'plm';
			$metabox_title   = _x( 'Places and Settings', 'metabox title', 'wpsso-plm' );
			$metabox_screen  = $this->pagehook;
			$metabox_context = 'normal';
			$metabox_prio    = 'default';
			$callback_args   = array(	// Second argument passed to the callback function / method.
			);

			add_meta_box( $this->pagehook . '_' . $metabox_id, $metabox_title,
				array( $this, 'show_metabox_' . $metabox_id ), $metabox_screen,
					$metabox_context, $metabox_prio, $callback_args );
		}

		public function show_metabox_plm() {

			$metabox_id = 'plm';

			$tabs = apply_filters( 'wpsso_' . $metabox_id . '_tabs', array( 
				'place'    => _x( 'Manage Places', 'metabox tab', 'wpsso-plm' ),
				'settings' => _x( 'Add-on Settings', 'metabox tab', 'wpsso-plm' ),
			) );

			$table_rows = array();

			foreach ( $tabs as $tab_key => $title ) {

				if ( isset( $this->p->avail[ 'p' ][ 'schema' ] ) && empty( $this->p->avail[ 'p' ][ 'schema' ] ) ) {	// Since WPSSO Core v6.23.3.

					$table_rows[ $tab_key ] = array();	// Older versions forced a reference argument.

					$table_rows[ $tab_key ] = $this->p->msgs->get_schema_disabled_rows( $table_rows[ $tab_key ] );

				} else {

					$filter_name = 'wpsso_' . $metabox_id . '_' . $tab_key . '_rows';

					$table_rows[ $tab_key ] = apply_filters( $filter_name, $this->get_table_rows( $metabox_id, $tab_key ), $this->form );
				}
			}

			$this->p->util->metabox->do_tabbed( $metabox_id, $tabs, $table_rows );
		}

		protected function get_table_rows( $metabox_id, $tab_key ) {

			$table_rows = array();

			switch ( $metabox_id . '-' . $tab_key ) {

				case 'plm-place':

					$weekdays =& $this->p->cf[ 'form' ][ 'weekdays' ];

					$def_schema_type = WpssoPlmConfig::$cf[ 'form' ][ 'plm_place_opts' ][ 'plm_place_schema_type' ];

					$place_names_select = WpssoPlmPlace::get_names( $schema_type = '', $add_none = false, $add_new = true, $add_custom = false );
					$place_types_select = $this->p->util->get_form_cache( 'place_types_select' );

					$this->form->defaults[ 'plm_place_id' ] = SucomUtil::get_last_num( $place_names_select );

					$table_rows[ 'plm_place_id' ] = '' . 
						$this->form->get_th_html( _x( 'Edit a Place', 'option label', 'wpsso-plm' ), $css_class = '', $css_id = 'plm_place_id' ) . 
						'<td>' . $this->form->get_select( 'plm_place_id', $place_names_select,
							$css_class = 'long_name', $css_id = '', $is_assoc = true, $is_disabled = false,
								$selected = true, $event_names = array( 'on_change_unhide_rows' ) ) . '</td>';

					foreach ( $place_names_select as $id => $name ) {

						$this->form->defaults[ 'plm_place_schema_type_' . $id ] = $def_schema_type;
						$this->form->defaults[ 'plm_place_country_' . $id ]     = $this->form->defaults[ 'plm_def_country' ];

						$tr_hide_place_html = '<!-- place id ' . $id . ' -->' . 
							'<tr class="hide_plm_place_id hide_plm_place_id_' . $id . '" style="display:none;">';

						$tr_hide_local_business_html = '<!-- place id ' . $id . ' -->' . 
							'<tr class="hide_plm_place_id ' . $this->p->schema->get_children_css_class( 'local.business',
								'hide_plm_place_schema_type_' . $id ) . '" style="display:none;">';

						$tr_hide_food_establishment_html = '<!-- place id ' . $id . ' -->' . 
							'<tr class="hide_plm_place_id ' . $this->p->schema->get_children_css_class( 'food.establishment',
								'hide_plm_place_schema_type_' . $id ) . '" style="display:none;">';

						$table_rows[ 'plm_place_delete_' . $id ] = $tr_hide_place_html . $this->form->get_th_html() . 
							'<td>' . $this->form->get_checkbox( 'plm_place_delete_' . $id ) . ' ' .
							'<em>' . _x( 'delete this place', 'option comment', 'wpsso-plm' ) . '</em></td>';

						$table_rows[ 'plm_place_schema_type_' . $id ] = $tr_hide_place_html . 
							$this->form->get_th_html( _x( 'Place Schema Type', 'option label', 'wpsso-plm' ),
								$css_class = '', $css_id = 'plm_place_schema_type' ) .  
							'<td>' . $this->form->get_select( 'plm_place_schema_type_' . $id, $place_types_select,
								$css_class = 'schema_type', $css_id = '', $is_assoc = true, $is_disabled = false,
									$selected = false, $event_names = array( 'on_focus_load_json', 'on_show_unhide_rows' ),
										$event_args = 'schema_place_types' ) . '</td>';

						$table_rows[ 'plm_place_name_' . $id ] = $tr_hide_place_html . 
							$this->form->get_th_html_locale( _x( 'Place Name', 'option label', 'wpsso-plm' ),
								$css_class = '', $css_id = 'plm_place_name' ) .
							'<td>' . $this->form->get_input_locale( 'plm_place_name_' . $id,
								$css_class = 'long_name is_required' ) . '</td>';

						$table_rows[ 'plm_place_name_alt_' . $id ] = $tr_hide_place_html . 
							$this->form->get_th_html_locale( _x( 'Place Alternate Name', 'option label', 'wpsso-plm' ),
								$css_class = '', $css_id = 'plm_place_name_alt' ) .
							'<td>' . $this->form->get_input_locale( 'plm_place_name_alt_' . $id,
								$css_class = 'long_name' ) . '</td>';

						$table_rows[ 'plm_place_desc_' . $id ] = $tr_hide_place_html . 
							$this->form->get_th_html_locale( _x( 'Place Description', 'option label', 'wpsso-plm' ),
								$css_class = '', $css_id = 'plm_place_desc' ) .
							'<td>' . $this->form->get_textarea_locale( 'plm_place_desc_' . $id ) . '</td>';

						$table_rows[ 'plm_place_street_address_' . $id ] = $tr_hide_place_html . 
							$this->form->get_th_html( _x( 'Street Address', 'option label', 'wpsso-plm' ),
								$css_class = '', $css_id = 'plm_place_street_address' ) .  
							'<td>' . $this->form->get_input( 'plm_place_street_address_' . $id, 'wide' ) . '</td>';

						$table_rows[ 'plm_place_po_box_number_' . $id ] = $tr_hide_place_html . 
							$this->form->get_th_html( _x( 'P.O. Box Number', 'option label', 'wpsso-plm' ),
								$css_class = '', $css_id = 'plm_place_po_box_number' ) .  
							'<td>' . $this->form->get_input( 'plm_place_po_box_number_' . $id ) . '</td>';

						$table_rows[ 'plm_place_city_' . $id ] = $tr_hide_place_html . 
							$this->form->get_th_html( _x( 'City / Locality', 'option label', 'wpsso-plm' ),
								$css_class = '', $css_id = 'plm_place_city' ) .  
							'<td>' . $this->form->get_input( 'plm_place_city_' . $id ) . '</td>';

						$table_rows[ 'plm_place_region_' . $id ] = $tr_hide_place_html . 
							$this->form->get_th_html( _x( 'State / Province', 'option label', 'wpsso-plm' ),
								$css_class = '', $css_id = 'plm_place_region' ) .  
							'<td>' . $this->form->get_input( 'plm_place_region_' . $id ) . '</td>';

						$table_rows[ 'plm_place_postal_code_' . $id ] = $tr_hide_place_html . 
							$this->form->get_th_html( _x( 'Zip / Postal Code', 'option label', 'wpsso-plm' ),
								$css_class = '', $css_id = 'plm_place_postal_code' ) .  
							'<td>' . $this->form->get_input( 'plm_place_postal_code_' . $id ) . '</td>';

						$table_rows[ 'plm_place_country_' . $id ] = $tr_hide_place_html . 
							$this->form->get_th_html( _x( 'Country', 'option label', 'wpsso-plm' ),
								$css_class = '', $css_id = 'plm_place_country' ) .  
							'<td>' . $this->form->get_select_country( 'plm_place_country_' . $id ) . '</td>';

						$table_rows[ 'plm_place_phone_' . $id ] = $tr_hide_place_html . 
							$this->form->get_th_html( _x( 'Telephone', 'option label', 'wpsso-plm' ),
								$css_class = '', $css_id = 'plm_place_phone' ) .  
							'<td>' . $this->form->get_input( 'plm_place_phone_' . $id ) . '</td>';

						$table_rows[ 'plm_place_latitude_' . $id ] = $tr_hide_place_html . 
							$this->form->get_th_html( _x( 'Place Latitude', 'option label', 'wpsso-plm' ),
								$css_class = '', $css_id = 'plm_place_latitude' ) .  
							'<td>' . $this->form->get_input( 'plm_place_latitude_' . $id, 'is_required' ) . ' ' . 
							_x( 'decimal degrees', 'option comment', 'wpsso-plm' ) . '</td>';

						$table_rows[ 'plm_place_longitude_' . $id ] = $tr_hide_place_html . 
							$this->form->get_th_html( _x( 'Place Longitude', 'option label', 'wpsso-plm' ),
								$css_class = '', $css_id = 'plm_place_longitude' ) .  
							'<td>' . $this->form->get_input( 'plm_place_longitude_' . $id, 'is_required' ) . ' ' . 
							_x( 'decimal degrees', 'option comment', 'wpsso-plm' ) . '</td>';

						$table_rows[ 'plm_place_altitude_' . $id ] = $tr_hide_place_html . 
							$this->form->get_th_html( _x( 'Place Altitude', 'option label', 'wpsso-plm' ),
								$css_class = '', $css_id = 'plm_place_altitude' ) .  
							'<td>' . $this->form->get_input( 'plm_place_altitude_' . $id ) . ' ' . 
							_x( 'meters above sea level', 'option comment', 'wpsso-plm' ) . '</td>';

						$table_rows[ 'plm_place_img_id_' . $id ] = $tr_hide_place_html . 
							$this->form->get_th_html_locale( _x( 'Place Image ID', 'option label', 'wpsso-plm' ),
								$css_class = '', $css_id = 'plm_place_img_id' ) .
							'<td>' . $this->form->get_input_image_upload( 'plm_place_img_' . $id ) . '</td>';

						$table_rows[ 'plm_place_img_url_' . $id ] = $tr_hide_place_html . 
							$this->form->get_th_html_locale( _x( 'or Place Image URL', 'option label', 'wpsso-plm' ),
								$css_class = '', $css_id = 'plm_place_img_url' ) .
							'<td>' . $this->form->get_input_image_url( 'plm_place_img_' . $id ) . '</td>';

						$table_rows[ 'plm_place_timezone_' . $id ] = $tr_hide_place_html .
							$this->form->get_th_html( _x( 'Place Timezone', 'option label', 'wpsso-plm' ),
								$css_class = '', $css_id = 'plm_place_timezone' ) .
							'<td>' . $this->form->get_select_timezone( 'plm_place_timezone_' . $id ) . '</td>';

						/**
						 * Example $weekdays = array(
						 *	'sunday'         => 'Sunday',
						 *	'monday'         => 'Monday',
						 *	'tuesday'        => 'Tuesday',
						 *	'wednesday'      => 'Wednesday',
						 *	'thursday'       => 'Thursday',
						 *	'friday'         => 'Friday',
						 *	'saturday'       => 'Saturday',
						 *	'publicholidays' => 'Public Holidays',
						 * );
						 */
						$open_close_html = '<table class="business_hours">';

						foreach ( $weekdays as $day_name => $day_label ) {

							$day_opt_pre   = 'plm_place_day_' . $day_name;
							$open_opt_key  = $day_opt_pre . '_open_' . $id;
							$close_opt_key = $day_opt_pre . '_close_' . $id;

							// translators: Please ignore - translation uses a different text domain.
							$day_label_transl = _x( $day_label, 'option value', 'wpsso' );

							$open_close_html .= '<tr>' .
								'<td class="weekday"><p>' . $day_label_transl . '</p></td>' .
								'<td align="right"><p>' . __( 'Opens at', 'wpsso-plm' ) . '</p></td>' .
								'<td>' . $this->form->get_select_time_none( $open_opt_key ) . '</td>' .
								'<td align="right"><p>' . __( 'and closes at', 'wpsso-plm' ) . '</p></td>' .
								'<td>' . $this->form->get_select_time_none( $close_opt_key ) . '</td>' .
								'</tr>';
						}

						$open_close_html .= '<tr>' .
							'<td><p>' . __( 'Every Midday', 'wpsso-plm' ) . '</p></td>' .
							'<td align="right"><p>' . __( 'Closes at', 'wpsso-plm' ) . '</p></td>' .
							'<td>' . $this->form->get_select_time_none( 'plm_place_midday_close_' . $id ) . '</td>' .
							'<td align="right"><p>' . __( 'and re-opens at', 'wpsso-plm' ) . '</p></td>' .
							'<td>' . $this->form->get_select_time_none( 'plm_place_midday_open_' . $id ) . '</td>' .
							'</tr>';

						$open_close_html .= '</table>';

						$table_rows[ 'plm_place_days_' . $id ] = $tr_hide_place_html .
							$this->form->get_th_html( _x( 'Open Days / Hours', 'option label', 'wpsso-plm' ),
								$css_class = '', $css_id = 'plm_place_days' ) .
							'<td>' . $open_close_html . '</td>';

						$table_rows[ 'plm_place_season_dates_' . $id ] = $tr_hide_place_html . 
							$this->form->get_th_html( _x( 'Seasonal Dates', 'option label', 'wpsso-plm' ),
								$css_class = '', $css_id = 'plm_place_season_dates' ) .  
							'<td><p style="margin-bottom:0;">' . 
							__( 'Open seasonally from', 'wpsso-plm' ) . ' ' .
							$this->form->get_input_date( 'plm_place_season_from_date_' . $id ) . ' ' . 
							__( 'until', 'wpsso-plm' ) . ' ' .
							$this->form->get_input_date( 'plm_place_season_to_date_' . $id ) . ' ' .
							__( 'inclusively', 'wpsso-plm' ) .
							'</p></td>';

						$table_rows[ 'subsection_local_business_' . $id ] = $tr_hide_local_business_html . '<th></th>' . 
							'<td class="subsection"><h5>' . _x( 'Local Business', 'metabox title', 'wpsso-plm' ) . '</h5></td>';

						$table_rows[ 'plm_place_service_radius_' . $id ] = $tr_hide_local_business_html .
							$this->form->get_th_html( _x( 'Service Radius', 'option label', 'wpsso-plm' ),
								$css_class = '', $css_id = 'plm_place_service_radius' ) .  
							'<td>' . $this->form->get_input( 'plm_place_service_radius_' . $id, $css_class = 'short' ) . ' ' . 
							_x( 'meters from location', 'option comment', 'wpsso-plm' ) . '</td>';

						foreach ( array(
							'currencies_accepted' => _x( 'Currencies Accepted', 'option label', 'wpsso-plm' ),
							'payment_accepted'    => _x( 'Payment Accepted', 'option label', 'wpsso-plm' ),
							'price_range'         => _x( 'Price Range', 'option label', 'wpsso-plm' ),
						) as $opt_name => $opt_label ) {

							$table_rows[ 'plm_place_' . $opt_name . '_' . $id ] = $tr_hide_local_business_html . 
								$this->form->get_th_html( $opt_label, $css_class = '', $css_id = 'plm_place_' . $opt_name ) .  
								'<td>' . $this->form->get_input( 'plm_place_' . $opt_name . '_' . $id ) . '</td>';
						}

						$table_rows[ 'subsection_food_establishment_' . $id ] = $tr_hide_food_establishment_html . '<th></th>' . 
							'<td class="subsection"><h5>' . _x( 'Food Establishment', 'metabox title', 'wpsso-plm' ) . '</h5></td>';

						$table_rows[ 'plm_place_accept_res_' . $id ] = $tr_hide_food_establishment_html . 
							$this->form->get_th_html( _x( 'Accepts Reservations', 'option label', 'wpsso-plm' ),
								$css_class = '', $css_id = 'plm_place_accept_res' ) .  
							'<td>' . $this->form->get_checkbox( 'plm_place_accept_res_' . $id ) . '</td>';

						$table_rows[ 'plm_place_cuisine_' . $id ] = $tr_hide_food_establishment_html . 
							$this->form->get_th_html( _x( 'Serves Cuisine', 'option label', 'wpsso-plm' ),
								$css_class = '', $css_id = 'plm_place_cuisine' ) .  
							'<td>' . $this->form->get_input( 'plm_place_cuisine_' . $id ) . '</td>';

						$table_rows[ 'plm_place_menu_url_' . $id ] = $tr_hide_food_establishment_html . 
							$this->form->get_th_html( _x( 'Food Menu URL', 'option label', 'wpsso-plm' ),
								$css_class = '', $css_id = 'plm_place_menu_url' ) .  
							'<td>' . $this->form->get_input( 'plm_place_menu_url_' . $id, 'wide' ) . '</td>';

						$table_rows[ 'plm_place_order_urls_' . $id ] = $tr_hide_food_establishment_html . 
							$this->form->get_th_html( _x( 'Order Action URL(s)', 'option label', 'wpsso-plm' ),
								$css_class = '', $css_id = 'plm_place_order_urls' ) .  
							'<td>' . $this->form->get_input( 'plm_place_order_urls_' . $id, 'wide' ) . '</td>';

					}

					break;

				case 'plm-settings':

					$add_to_checkboxes = '';

					foreach ( SucomUtilWP::get_post_types( 'objects' ) as $obj ) {

						$add_to_checkboxes .= '<p>' . $this->form->get_checkbox( 'plm_add_to_' . $obj->name ) . ' ' .
							( empty( $obj->label ) ? '' : $obj->label ) . 	// Just in case.
							( empty( $obj->description ) ? '' : ' (' . $obj->description . ')' ) . '</p>';
					}

					$table_rows[ 'plm_add_to' ] = '' . 
						$this->form->get_th_html( _x( 'Show Tab on Post Types', 'option label', 'wpsso-plm' ),
							$css_class = '', $css_id = 'plm_add_to' ) . 
						'<td>' . $add_to_checkboxes . '</td>';

					$table_rows[ 'plm_def_country' ] = '' . 
						$this->form->get_th_html( _x( 'Default Country', 'option label', 'wpsso-plm' ),
							$css_class = '', $css_id = 'plm_def_country' ) . 
						'<td>' . $this->form->get_select_country( 'plm_def_country' ) . '</td>';

					break;

			}

			return $table_rows;
		}
	}
}
