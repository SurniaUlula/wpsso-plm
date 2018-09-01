<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2018 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for...' );
}

if ( ! class_exists( 'WpssoPlmSubmenuPlmGeneral' ) && class_exists( 'WpssoAdmin' ) ) {

	class WpssoPlmSubmenuPlmGeneral extends WpssoAdmin {

		public function __construct( &$plugin, $id, $name, $lib, $ext ) {
			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			$this->menu_id = $id;
			$this->menu_name = $name;
			$this->menu_lib = $lib;
			$this->menu_ext = $ext;
		}

		/**
		 * Called by the extended WpssoAdmin class.
		 */
		protected function add_meta_boxes() {

			add_meta_box( $this->pagehook . '_place', 
				_x( 'Places / Locations and Settings', 'metabox title', 'wpsso-plm' ), 
					array( $this, 'show_metabox_place' ), $this->pagehook, 'normal' );
		}

		public function show_metabox_place() {

			$metabox_id = 'plm';

			$tabs = apply_filters( $this->p->lca . '_' . $metabox_id . '_tabs', array( 
				'place'    => _x( 'Manage Places', 'metabox tab', 'wpsso-plm' ),
				'settings' => _x( 'Add-on Settings', 'metabox tab', 'wpsso-plm' ),
			) );

			$table_rows = array();

			foreach ( $tabs as $tab_key => $title ) {
				$table_rows[ $tab_key ] = apply_filters( $this->p->lca . '_' . $metabox_id . '_' . $tab_key . '_rows', 
					$this->get_table_rows( $metabox_id, $tab_key ), $this->form );
			}

			$this->p->util->do_metabox_tabbed( $metabox_id, $tabs, $table_rows );
		}

		protected function get_table_rows( $metabox_id, $tab_key ) {

			$table_rows = array();

			switch ( $metabox_id . '-' . $tab_key ) {

				case 'plm-place':

					$place_names_select = WpssoPlmPlace::get_names( '', false, true, false );	// $add_none is false, $add_new is true.
					$place_first_num    = SucomUtil::get_first_num( $place_names_select );
					$place_types_select = $this->p->util->get_form_cache( 'place_types_select' );
					$half_hours         = $this->p->util->get_form_cache( 'half_hours' );

					$this->form->defaults['plm_place_id'] = $place_first_num;	// Set default value.

					/**
					 * Check to make sure the selected id exists - if not, then unset and use the default.
					 */
					if ( isset( $this->form->options['plm_place_id'] ) ) {

						$def_id = $this->form->options['plm_place_id'];

						/**
						 * Test if the place name is missing or blank.
						 */
						if ( ! isset( $this->p->options['plm_place_name_' . $def_id] ) ||
							trim( $this->p->options['plm_place_name_' . $def_id] ) === '' ) {

							unset( $this->form->options['plm_place_id'] );
						}
					}

					$table_rows['plm_place_id'] = '' . 
					$this->form->get_th_html( _x( 'Edit a Place', 'option label', 'wpsso-plm' ), '', 'plm_place_id' ) . 
					'<td colspan="3">' . $this->form->get_select( 'plm_place_id', $place_names_select,
						'long_name', '', true, false, true, 'unhide_rows' ) . '</td>';

					foreach ( $place_names_select as $id => $name ) {

						$def_schema_type = WpssoPlmConfig::$cf['form']['plm_place_opts']['plm_place_schema_type'];

						$this->form->defaults['plm_place_schema_type_' . $id] = $def_schema_type;
						$this->form->defaults['plm_place_country_' . $id]     = $this->p->options['plm_place_def_country'];

						foreach ( $this->p->cf['form']['weekdays'] as $day => $day_label ) {
							$this->form->defaults['plm_place_day_' . $day . '_' . $id]       = '0';
							$this->form->defaults['plm_place_day_' . $day . '_open_' . $id]  = '09:00';
							$this->form->defaults['plm_place_day_' . $day . '_close_' . $id] = '17:00';
						}

						$tr_hide_place_html = '<!-- place id ' . $id . ' -->' . 
							'<tr class="hide_plm_place_id hide_plm_place_id_' . $id . '" style="display:none">';

						$tr_hide_local_business_html = '<!-- place id ' . $id . ' -->' . 
							'<tr class="hide_plm_place_id ' . $this->p->schema->get_children_css_class( 'local.business',
								'hide_plm_place_schema_type_' . $id ) . '" style="display:none">';

						$tr_hide_food_establishment_html = '<!-- place id ' . $id . ' -->' . 
							'<tr class="hide_plm_place_id ' . $this->p->schema->get_children_css_class( 'food.establishment',
								'hide_plm_place_schema_type_' . $id ) . '" style="display:none">';

						$table_rows['plm_place_delete_' . $id] = $tr_hide_place_html .
						$this->form->get_th_html() . 
						'<td colspan="3">' . $this->form->get_checkbox( 'plm_place_delete_' . $id ) . ' ' .
						'<em>' . _x( 'delete this place', 'option comment', 'wpsso-plm' ) . '</em></td>';
		
						$table_rows['plm_place_schema_type_' . $id] = $tr_hide_place_html . 
						$this->form->get_th_html( _x( 'Place Schema Type', 'option label', 'wpsso-plm' ), '', 'plm_place_schema_type' ) .  
						'<td colspan="3">' . $this->form->get_select( 'plm_place_schema_type_' . $id, $place_types_select,
							'schema_type', '', true, false, true, 'unhide_rows_on_show' ) . '</td>';
	
						$table_rows['plm_place_name_' . $id] = $tr_hide_place_html . 
						$this->form->get_th_html( _x( 'Place Name', 'option label', 'wpsso-plm' ), '', 'plm_place_name',
							array( 'is_locale' => true ) ) .
						'<td colspan="3">' . $this->form->get_input( 'plm_place_name_' . $id, 'long_name required' ) . '</td>';

						$table_rows['plm_place_name_alt_' . $id] = $tr_hide_place_html . 
						$this->form->get_th_html( _x( 'Place Alternate Name', 'option label', 'wpsso-plm' ), '', 'plm_place_name_alt',
							array( 'is_locale' => true ) ) .
						'<td colspan="3">' . $this->form->get_input( 'plm_place_name_alt_' . $id, 'long_name' ) . '</td>';

						$table_rows['plm_place_desc_' . $id] = $tr_hide_place_html . 
						$this->form->get_th_html( _x( 'Place Description', 'option label', 'wpsso-plm' ), '', 'plm_place_desc',
							array( 'is_locale' => true ) ) .
						'<td colspan="3">' . $this->form->get_textarea( SucomUtil::get_key_locale( 'plm_place_desc_' . $id,
							$this->form->options ) ) . '</td>';

						$table_rows['plm_place_street_address_' . $id] = $tr_hide_place_html . 
						$this->form->get_th_html( _x( 'Street Address', 'option label', 'wpsso-plm' ), '', 'plm_place_street_address' ) .  
						'<td colspan="3">' . $this->form->get_input( 'plm_place_street_address_' . $id, 'wide' ) . '</td>';
		
						$table_rows['plm_place_po_box_number_' . $id] = $tr_hide_place_html . 
						$this->form->get_th_html( _x( 'P.O. Box Number', 'option label', 'wpsso-plm' ), '', 'plm_place_po_box_number' ) .  
						'<td colspan="3">' . $this->form->get_input( 'plm_place_po_box_number_' . $id ) . '</td>';
		
						$table_rows['plm_place_city_' . $id] = $tr_hide_place_html . 
						$this->form->get_th_html( _x( 'City', 'option label', 'wpsso-plm' ), '', 'plm_place_city' ) .  
						'<td colspan="3">' . $this->form->get_input( 'plm_place_city_' . $id ) . '</td>';
		
						$table_rows['plm_place_state_' . $id] = $tr_hide_place_html . 
						$this->form->get_th_html( _x( 'State / Province', 'option label', 'wpsso-plm' ), '', 'plm_place_state' ) .  
						'<td colspan="3">' . $this->form->get_input( 'plm_place_state_' . $id ) . '</td>';
		
						$table_rows['plm_place_zipcode_' . $id] = $tr_hide_place_html . 
						$this->form->get_th_html( _x( 'Zip / Postal Code', 'option label', 'wpsso-plm' ), '', 'plm_place_zipcode' ) .  
						'<td colspan="3">' . $this->form->get_input( 'plm_place_zipcode_' . $id ) . '</td>';
		
						$table_rows['plm_place_country_' . $id] = $tr_hide_place_html . 
						$this->form->get_th_html( _x( 'Country', 'option label', 'wpsso-plm' ), '', 'plm_place_country' ) .  
						'<td colspan="3">' . $this->form->get_select_country( 'plm_place_country_' . $id ) . '</td>';

						$table_rows['plm_place_phone_' . $id] = $tr_hide_place_html . 
						$this->form->get_th_html( _x( 'Telephone', 'option label', 'wpsso-plm' ), '', 'plm_place_phone' ) .  
						'<td colspan="3">' . $this->form->get_input( 'plm_place_phone_' . $id ) . '</td>';

						$table_rows['plm_place_latitude_' . $id] = $tr_hide_place_html . 
						$this->form->get_th_html( _x( 'Place Latitude', 'option label', 'wpsso-plm' ), '', 'plm_place_latitude' ) .  
						'<td colspan="3">' . $this->form->get_input( 'plm_place_latitude_' . $id, 'required' ) . ' ' . 
						_x( 'decimal degrees', 'option comment', 'wpsso-plm' ) . '</td>';
		
						$table_rows['plm_place_longitude_' . $id] = $tr_hide_place_html . 
						$this->form->get_th_html( _x( 'Place Longitude', 'option label', 'wpsso-plm' ), '', 'plm_place_longitude' ) .  
						'<td colspan="3">' . $this->form->get_input( 'plm_place_longitude_' . $id, 'required' ) . ' ' . 
						_x( 'decimal degrees', 'option comment', 'wpsso-plm' ) . '</td>';
		
						$table_rows['plm_place_altitude_' . $id] = $tr_hide_place_html . 
						$this->form->get_th_html( _x( 'Place Altitude', 'option label', 'wpsso-plm' ), '', 'plm_place_altitude' ) .  
						'<td colspan="3">' . $this->form->get_input( 'plm_place_altitude_' . $id ) . ' ' . 
						_x( 'meters above sea level', 'option comment', 'wpsso-plm' ) . '</td>';

						$table_rows['plm_place_img_id_' . $id] = $tr_hide_place_html . 
						$this->form->get_th_html( _x( 'Place Image ID', 'option label', 'wpsso-plm' ), '', 'plm_place_img_id',
							array( 'is_locale' => true ) ) .
						'<td colspan="3">' . $this->form->get_input_image_upload( 'plm_place_img_' . $id ) . '</td>';
	
						$table_rows['plm_place_img_url_' . $id] = $tr_hide_place_html . 
						$this->form->get_th_html( _x( 'or Place Image URL', 'option label', 'wpsso-plm' ), '', 'plm_place_img_url',
							array( 'is_locale' => true ) ) .
						'<td colspan="3">' . $this->form->get_input_image_url( 'plm_place_img_' . $id ) . '</td>';

						$row_number = 1;

						foreach ( $this->p->cf['form']['weekdays'] as $day => $day_label ) {

							$day_label_transl = _x( $day_label, 'option value', 'wpsso' );

							if ( $row_number === 1 ) {
								$th_cell_html = $tr_hide_place_html .
									$this->form->get_th_html( _x( 'Open Days / Hours',
										'option label', 'wpsso-plm' ), '', 'plm_place_days' );
							} else {
								$th_cell_html = $tr_hide_place_html . '<th></th>';
							}
		
							$table_rows['plm_place_day_' . $day . '_' . $id] = $th_cell_html . 
							'<td class="weekday">' . $this->form->get_checkbox( 'plm_place_day_' . $day . '_' . $id ) . ' ' .
								$day_label_transl . '</td>' . 
							'<td>' . __( 'Opens at', 'wpsso-plm' ) . ' ' . $this->form->get_select( 'plm_place_day_' . $day . '_open_' . $id,
								$half_hours, 'medium', '', true ) . '</td>' . 
							'<td>' . __( 'Closes at', 'wpsso-plm' ) . ' ' . $this->form->get_select( 'plm_place_day_' . $day . '_close_' . $id,
								$half_hours, 'medium', '', true ) . '</td>';

							$row_number++;
						}
		
						$table_rows['plm_place_season_dates_' . $id] = $tr_hide_place_html . 
						$this->form->get_th_html( _x( 'Open Dates (Seasonal)', 'option label', 'wpsso-plm' ), '', 'plm_place_season_dates' ) .  
						'<td colspan="3">' . 
							__( 'Open from', 'wpsso-plm' ) . ' ' . $this->form->get_input_date( 'plm_place_season_from_date_' . $id ) . ' ' . 
							__( 'through', 'wpsso-plm' ) . ' ' . $this->form->get_input_date( 'plm_place_season_to_date_' . $id ) .
						'</td>';
		
						$table_rows['subsection_local_business_' . $id] = $tr_hide_local_business_html . '<th></th>' . 
						'<td class="subsection" colspan="3"><h5>' . _x( 'Local Business', 'metabox title', 'wpsso-plm' ) . '</h5></td>';

						$table_rows['plm_place_service_radius_' . $id] = $tr_hide_local_business_html .
						$this->form->get_th_html( _x( 'Service Radius', 'option label', 'wpsso-plm' ), '', 'plm_place_service_radius' ) .  
						'<td colspan="3">' . $this->form->get_input( 'plm_place_service_radius_' . $id, 'medium' ) . ' ' . 
						_x( 'meters from location', 'option comment', 'wpsso-plm' ) . '</td>';
		
						foreach ( array(
							'currencies_accepted' => _x( 'Currencies Accepted', 'option label', 'wpsso-plm' ),
							'payment_accepted' => _x( 'Payment Accepted', 'option label', 'wpsso-plm' ),
							'price_range' => _x( 'Price Range', 'option label', 'wpsso-plm' ),
						) as $opt_name => $opt_label ) {

							$table_rows['plm_place_' . $opt_name . '_' . $id] = $tr_hide_local_business_html . 
							$this->form->get_th_html( $opt_label, '', 'plm_place_' . $opt_name ) .  
							'<td colspan="3">' . $this->form->get_input( 'plm_place_' . $opt_name . '_' . $id ) . '</td>';
						}
		
						$table_rows['subsection_food_establishment_' . $id] = $tr_hide_food_establishment_html . '<th></th>' . 
						'<td class="subsection" colspan="3"><h5>' . _x( 'Food Establishment', 'metabox title', 'wpsso-plm' ) . '</h5></td>';

						$table_rows['plm_place_accept_res_' . $id] = $tr_hide_food_establishment_html . 
						$this->form->get_th_html( _x( 'Accepts Reservations', 'option label', 'wpsso-plm' ), '', 'plm_place_accept_res' ) .  
						'<td colspan="3">' . $this->form->get_checkbox( 'plm_place_accept_res_' . $id ) . '</td>';

						$table_rows['plm_place_cuisine_' . $id] = $tr_hide_food_establishment_html . 
						$this->form->get_th_html( _x( 'Serves Cuisine', 'option label', 'wpsso-plm' ), '', 'plm_place_cuisine' ) .  
						'<td colspan="3">' . $this->form->get_input( 'plm_place_cuisine_' . $id ) . '</td>';

						$table_rows['plm_place_menu_url_' . $id] = $tr_hide_food_establishment_html . 
						$this->form->get_th_html( _x( 'Food Menu URL', 'option label', 'wpsso-plm' ), '', 'plm_place_menu_url' ) .  
						'<td colspan="3">' . $this->form->get_input( 'plm_place_menu_url_' . $id, 'wide' ) . '</td>';

						$table_rows['plm_place_order_urls_' . $id] = $tr_hide_food_establishment_html . 
						$this->form->get_th_html( _x( 'Order Action URL(s)', 'option label', 'wpsso-plm' ), '', 'plm_place_order_urls' ) .  
						'<td colspan="3">' . $this->form->get_input( 'plm_place_order_urls_' . $id, 'wide' ) . '</td>';

					}

					break;

				case 'plm-settings':

					$table_rows['plm_place_def_country'] = '' . 
					$this->form->get_th_html( _x( 'Place Default Country', 'option label', 'wpsso-plm' ), '', 'plm_place_def_country' ) . 
					'<td>' . $this->form->get_select_country( 'plm_place_def_country' ) . '</td>';

					$add_to_checkboxes = '';

					foreach ( $this->p->util->get_post_types( 'objects' ) as $pt ) {
						$add_to_checkboxes .= '<p>' .
							$this->form->get_checkbox( 'plm_add_to_' . $pt->name ) . ' ' .
							( empty( $pt->label ) ? '' : $pt->label ) . 	// Just in case.
							( empty( $pt->description ) ? '' : ' (' . $pt->description . ')' ) .
							'</p>';
					}

					$table_rows['plm_add_to'] = '' . 
					$this->form->get_th_html( _x( 'Show Tab on Post Types', 'option label', 'wpsso-plm' ), '', 'plm_add_to' ) . 
					'<td>' . $add_to_checkboxes . '</td>';

					break;

			}

			return $table_rows;
		}
	}
}
