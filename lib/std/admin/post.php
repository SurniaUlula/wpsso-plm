<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2019 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for...' );
}

if ( ! class_exists( 'WpssoPlmStdAdminPost' ) ) {

	class WpssoPlmStdAdminPost {

		private $p;

		public function __construct( &$plugin ) {

			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			$this->p->util->add_plugin_filters( $this, array( 
				'post_place_rows' => 4,
			) );
		}

		public function filter_post_place_rows( $table_rows, $form, $head, $mod ) {

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			$place_names_select = array( 'none' => $this->p->cf[ 'form' ][ 'place_select' ][ 'none' ] );
			$place_types_select = $this->p->util->get_form_cache( 'place_types_select' );

			unset( $form->options[ 'plm_place_id' ] );

			$table_rows[] = '<td colspan="3">' . $this->p->msgs->get( 'info-plm-place' ) . '</td>';

			$table_rows[] = '<td colspan="3">' . $this->p->msgs->get( 'pro-feature-msg', array( 'lca' => 'wpssoplm' ) ) . '</td>';

			$table_rows[ 'plm_place_id' ] = '' . 
			$form->get_th_html( _x( 'Select a Place', 'option label', 'wpsso-plm' ), 'medium', 'post-plm_place_id' ) . 
			'<td class="blank" colspan="2">' . $form->get_no_select( 'plm_place_id', $place_names_select, 'long_name', '', true ) . '</td>';

			$table_rows[ 'plm_place_schema_type' ] = '' . 
			$form->get_th_html( _x( 'Place Schema Type', 'option label', 'wpsso-plm' ), 'medium', 'plm_place_schema_type' ) .  
			'<td class="blank" colspan="2">' . $form->get_no_select( 'plm_place_schema_type', $place_types_select,
				'schema_type', '', true ) . '</td>';

			$table_rows[ 'plm_place_name_alt' ] = '' . 
			$form->get_th_html( _x( 'Place Alternate Name', 'option label', 'wpsso-plm' ), 'medium', 'plm_place_name_alt' ) .  
			'<td class="blank" colspan="2">' . $form->get_no_input_value( '', 'long_name' ) . '</td>';

			$table_rows[ 'plm_place_street_address' ] = '' . 
			$form->get_th_html( _x( 'Street Address', 'option label', 'wpsso-plm' ), 'medium', 'plm_place_street_address' ) .  
			'<td class="blank" colspan="2">' . $form->get_no_input_value( '', 'wide' ) . '</td>';

			$table_rows[ 'plm_place_po_box_number' ] = '' . 
			$form->get_th_html( _x( 'P.O. Box Number', 'option label', 'wpsso-plm' ), 'medium', 'plm_place_po_box_number' ) .  
			'<td class="blank" colspan="2">' . $form->get_no_input_value() . '</td>';

			$table_rows[ 'plm_place_city' ] = '' . 
			$form->get_th_html( _x( 'City', 'option label', 'wpsso-plm' ), 'medium', 'plm_place_city' ) .  
			'<td class="blank" colspan="2">' . $form->get_no_input_value() . '</td>';

			$table_rows[ 'plm_place_state' ] = '' . 
			$form->get_th_html( _x( 'State / Province', 'option label', 'wpsso-plm' ), 'medium', 'plm_place_state' ) .  
			'<td class="blank" colspan="2">' . $form->get_no_input_value() . '</td>';

			$table_rows[ 'plm_place_zipcode' ] = '' . 
			$form->get_th_html( _x( 'Zip / Postal Code', 'option label', 'wpsso-plm' ), 'medium', 'plm_place_zipcode' ) .  
			'<td class="blank" colspan="2">' . $form->get_no_input_value() . '</td>';

			$table_rows[ 'plm_place_country' ] = '' . 
			$form->get_th_html( _x( 'Country', 'option label', 'wpsso-plm' ), 'medium', 'plm_place_country' ) .  
			'<td class="blank"colspan="2">' . $form->get_no_select_country( 'plm_place_country' ) . '</td>';

			$table_rows[ 'plm_place_phone' ] = '' . 
			$form->get_th_html( _x( 'Telephone', 'option label', 'wpsso-plm' ), 'medium', 'plm_place_phone' ) .  
			'<td class="blank" colspan="2">' . $form->get_no_input_value( '' ) . '</td>';

			$table_rows[ 'plm_place_latitude' ] = '' . 
			$form->get_th_html( _x( 'Place Latitude', 'option label', 'wpsso-plm' ), 'medium', 'plm_place_latitude' ) .  
			'<td class="blank" colspan="2">' . $form->get_no_input( '', 'required' ) . ' ' . 
			_x( 'decimal degrees', 'option comment', 'wpsso-plm' ) . '</td>';

			$table_rows[ 'plm_place_longitude' ] = '' . 
			$form->get_th_html( _x( 'Place Longitude', 'option label', 'wpsso-plm' ), 'medium', 'plm_place_longitude' ) .  
			'<td class="blank" colspan="2">' . $form->get_no_input( '', 'required' ) . ' ' . 
			_x( 'decimal degrees', 'option comment', 'wpsso-plm' ) . '</td>';

			$table_rows[ 'plm_place_altitude' ] = '' . 
			$form->get_th_html( _x( 'Place Altitude', 'option label', 'wpsso-plm' ), 'medium', 'plm_place_altitude' ) .  
			'<td class="blank" colspan="2">' . $form->get_no_input() . ' ' . 
			_x( 'meters above sea level', 'option comment', 'wpsso-plm' ) . '</td>';

			$row_number = 1;

			foreach ( $this->p->cf[ 'form' ][ 'weekdays' ] as $day => $day_label ) {

				$day_label_transl = _x( $day_label, 'option value', 'wpsso' );

				if ( $row_number === 1 ) {
					$th_cell_html = $form->get_th_html( _x( 'Open Days / Hours',
						'option label', 'wpsso-plm' ), 'medium', 'plm_place_days' );
				} else {
					$th_cell_html = '<td></td>';
				}

				$table_rows[ 'plm_place_day_' . $day ] = $th_cell_html . 
				'<td class="blank weekday">' . $form->get_no_checkbox( 'plm_place_day_' . $day ) . ' ' . $day_label_transl . '</td>' . 
				'<td class="blank">' .
				__( 'Opens at', 'wpsso-plm' ) . ' ' . $form->get_no_select_time( 'plm_place_day_' . $day . '_open' ) .
				__( 'and closes at', 'wpsso-plm' ) . ' ' . $form->get_no_select_time( 'plm_place_day_' . $day . '_close' ) .
				'</td>';

				$row_number++;
			}

			$table_rows[ 'plm_place_midday_hours' ] = '' . 
			$form->get_th_html( _x( 'Closes Mid-Day', 'option label', 'wpsso-plm' ), '', 'plm_place_midday_hours' ) .  
			'<td class="blank" colspan="2">' .
			__( 'Closed from', 'wpsso-plm' ) . ' ' . $form->get_no_select_time( 'plm_place_midday_close' ).
			__( 'to', 'wpsso-plm' ) . ' ' . $form->get_no_select_time( 'plm_place_midday_open' ) .
			'</td>';

			$table_rows[ 'plm_place_season_dates' ] = '' . 
			$form->get_th_html( _x( 'Seasonal Dates', 'option label', 'wpsso-plm' ), 'medium', 'plm_place_season_dates' ) .  
			'<td class="blank" colspan="2">' .
			__( 'Open from', 'wpsso-plm' ) . ' ' . $form->get_no_input_date() . ' ' . 
			__( 'through', 'wpsso-plm' ) . ' ' . $form->get_no_input_date() .
			__( 'inclusively', 'wpsso-plm' ) .
			'</td>';

			$table_rows[ 'subsection_local_business' ] = '' .
			'<th class="medium"></th>' . 
			'<td class="subsection" colspan="2"><h5>' . _x( 'Local Business', 'metabox title', 'wpsso-plm' ) . '</h5></td>';

			$table_rows[ 'plm_place_service_radius' ] = '' .
			$form->get_th_html( _x( 'Service Radius', 'option label', 'wpsso-plm' ), 'medium', 'plm_place_service_radius' ) .  
			'<td class="blank" colspan="2">' . $form->get_no_input_value( '', 'medium' ) . ' ' . 
			_x( 'meters from location', 'option comment', 'wpsso-plm' ) . '</td>';

			foreach ( array(
				'currencies_accepted' => _x( 'Currencies Accepted', 'option label', 'wpsso-plm' ),
				'payment_accepted'    => _x( 'Payment Accepted', 'option label', 'wpsso-plm' ),
				'price_range'         => _x( 'Price Range', 'option label', 'wpsso-plm' ),
			) as $opt_name => $opt_label ) {

				$table_rows[ 'plm_place_' . $opt_name] = ''.
				$form->get_th_html( $opt_label, 'medium', 'plm_place_' . $opt_name ) .  
				'<td class="blank" colspan="2">' . $form->get_no_input_value( '' ) . '</td>';
			}

			$table_rows[ 'subsection_food_establishment' ] = '' .
			'<th class="medium"></th>' . 
			'<td class="subsection" colspan="2"><h5>' . _x( 'Food Establishment', 'metabox title', 'wpsso-plm' ) . '</h5></td>';

			$table_rows[ 'plm_place_accept_res' ] = ''.
			$form->get_th_html( _x( 'Accepts Reservations', 'option label', 'wpsso-plm' ), 'medium', 'plm_place_accept_res' ) .  
			'<td class="blank" colspan="2">' . $form->get_no_checkbox( 'plm_place_accept_res' ) . '</td>';

			$table_rows[ 'plm_place_cuisine' ] = ''.
			$form->get_th_html( _x( 'Serves Cuisine', 'option label', 'wpsso-plm' ), 'medium', 'plm_place_cuisine' ) .  
			'<td class="blank" colspan="2">' . $form->get_no_input_value( '' ) . '</td>';

			$table_rows[ 'plm_place_menu_url' ] = ''.
			$form->get_th_html( _x( 'Food Menu URL', 'option label', 'wpsso-plm' ), 'medium', 'plm_place_menu_url' ) .  
			'<td class="blank" colspan="2">' . $form->get_no_input_value( '', 'wide' ) . '</td>';

			$table_rows[ 'plm_place_order_urls' ] = ''.
			$form->get_th_html( _x( 'Order Action URL(s)', 'option label', 'wpsso-plm' ), 'medium', 'plm_place_order_urls' ) .  
			'<td class="blank" colspan="2">' . $form->get_no_input_value( '', 'wide' ) . '</td>';

			return $table_rows;
		}
	}
}
