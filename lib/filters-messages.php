<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2020 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoPlmFiltersMessages' ) ) {

	class WpssoPlmFiltersMessages {

		private $p;

		/**
		 * Instantiated by WpssoPlmFilters->__construct().
		 */
		public function __construct( &$plugin ) {

			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			if ( is_admin() ) {

				$this->p->util->add_plugin_filters( $this, array( 
					'messages_info'         => 2,
					'messages_tooltip'      => 2,
					'messages_tooltip_meta' => 2,
				) );
			}
		}

		public function filter_messages_info( $text, $msg_key ) {

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			if ( strpos( $msg_key, 'info-plm-' ) !== 0 ) {
				return $text;
			}

			switch ( $msg_key ) {

				case 'info-plm-place':

					$text = '<blockquote class="top-info">';

					$text .= '<p>';

					$text .= __( 'You may select a place or enter custom place information to further describe the content subject.', 'wpsso-plm' ) . ' ';

					$text .= sprintf( __( 'Please make sure the content subject is about a single specific place or location - for example, <a href="%s">The Eiffel Tower</a>.', 'wpsso-plm' ), __( 'https://en.wikipedia.org/wiki/Eiffel_Tower', 'wpsso-plm' ) );

					$text .= '</p><p>';

					$text .= __( 'Selecting a place will define the webpage Open Graph type to "place" and the Schema type to the one selected below.', 'wpsso-plm' );

					$text .= '</p>';

					$text .= '</blockquote>';

					break;

			}

			return $text;
		}

		public function filter_messages_tooltip( $text, $msg_key ) {

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			if ( strpos( $msg_key, 'tooltip-plm_' ) !== 0 ) {
				return $text;
			}

			switch ( $msg_key ) {

				case 'tooltip-plm_place_id':

					$text = __( 'Select a place to edit.', 'wpsso-plm' );

					$text .= __( 'The place information is used for Open Graph meta tags and Schema markup.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_schema_type':	// Place Schema Type.

					$text = __( 'You may optionally choose a different Schema type for this place (default is LocalBusiness).', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_name':

					$text = __( 'A name for this place (required).', 'wpsso-plm' );

					$text .= __( 'The place name may appear in forms and in the Schema Place "name" property.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_name_alt':

					$text = __( 'An alternate name for this place.', 'wpsso-plm' );

					$text .= __( 'The place alternate name may appear in the Schema Place "alternateName" property.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_desc':

					$text = __( 'A description for this place.', 'wpsso-plm' );

					$text .= __( 'The place description may appear in the Schema Place "description" property.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_street_address':

					$text = __( 'An optional street address for Pinterest Rich Pin / Schema Place meta tags and related markup.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_po_box_number':

					$text = __( 'An optional post office box number for the Pinterest Rich Pin / Schema Place meta tags and related markup.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_city':

					$text = __( 'An optional city name for the Pinterest Rich Pin / Schema Place meta tags and related markup.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_state':

					$text = __( 'An optional state or Province name for the Pinterest Rich Pin / Schema Place meta tags and related markup.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_zipcode':

					$text = __( 'An optional zip or postal code for the Pinterest Rich Pin / Schema Place meta tags and related markup.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_country':

					$text = __( 'An optional country for the Pinterest Rich Pin / Schema Place meta tags and related markup.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_phone':

					$text = __( 'An optional telephone number for this place.', 'wpsso-plm' );

					break;
				case 'tooltip-plm_place_latitude':

					$text = __( 'The numeric decimal degrees latitude for this place (required).', 'wpsso-plm' ) . ' ';
					
					$text .= __( 'You may use a service like <a href="http://www.gps-coordinates.net/">Google Maps GPS Coordinates</a> (as an example), to find the approximate GPS coordinates of a street address.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_longitude':

					$text = __( 'The numeric decimal degrees longitude for this place (required).', 'wpsso-plm' ) . ' ';
					
					$text .= __( 'You may use a service like <a href="http://www.gps-coordinates.net/">Google Maps GPS Coordinates</a> (as an example), to find the approximate GPS coordinates of a street address.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_altitude':

					$text = __( 'An optional numeric altitude (in meters above sea level) for this place.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_img_id':	// Place Image ID.

					$text = __( 'An image ID and media library selection for this place (ie. an image of the business storefront or location).', 'wpsso-plm' ) . ' ';

					$text .= __( 'The place image is used in the Schema LocalBusiness markup for the Schema "location" property.', 'wpsso-plm' ) . ' ';

					$text .= __( 'The place image is not used when a place is selected for the post, page, or custom post type content &mdash; in this case, the custom and/or featured image is used.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_img_url':	// or Place Image URL.

					$text = __( 'You can enter a place image URL (including the http:// prefix) instead of choosing an image ID &mdash; if a place image ID is specified, it has precedence and the image URL option is disabled.', 'wpsso-plm' ) . ' ';
					
					$text .= __( 'The image URL option allows you to use an image outside of a managed collection (WordPress Media Library or NextGEN Gallery), and/or a smaller logo style image.', 'wpsso-plm' ) . ' ';

					$text .= __( 'The place image is used in the Schema LocalBusiness markup for the Schema "location" property.', 'wpsso-plm' ) . ' ';

					$text .= __( 'The place image is not used when a place is selected for the post, page, or custom post type content &mdash; in this case, the custom and/or featured image is used.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_days':		// Open Days / Hours.

					$text = __( 'Select the days and hours this place is open.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_midday_hours':	// Closes Mid-Day.

					$text = __( 'This place closes temporarily mid-day (for example, between 12:00 and 13:00 for lunch).', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_season_dates':	// Seasonal Dates.

					$text = __( 'If this place is open seasonally, select the open and close dates of the season.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_service_radius':

					$text = __( 'The geographic area where a service is provided, in meters around the location.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_currencies_accepted':

					$text = sprintf( __( 'A comma-delimited list of <a href="%1$s">ISO 4217 currency codes</a> accepted by the local business (example: %2$s).', 'wpsso-plm' ), 'https://en.wikipedia.org/wiki/ISO_4217', 'USD, CAD' );

					break;

				case 'tooltip-plm_place_payment_accepted':

					$text = __( 'A comma-delimited list of payment options accepted by the local business (example: Cash, Credit Card).', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_price_range':

					$text = __( 'The relative price of goods or services provided by the local business (example: $, $$, $$$, or $$$$).', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_accept_res':

					$text = __( 'This food establishment accepts reservations.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_menu_url':

					$text = __( 'The menu URL for this food establishment.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_cuisine':

					$text = __( 'The cuisine served by this food establishment.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_place_order_urls':

					$text = __( 'A comma-delimited list of website and mobile app URLs to order products.', 'wpsso-plm' ) . ' ';
					
					$text .= __( 'The WPSSO JSON add-on is required to add these Order Action URL(s) to the Schema "potentialAction" property.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_def_country':

					$text = __( 'A default country to use when creating a new place.', 'wpsso-plm' );

					break;

				case 'tooltip-plm_add_to':

					$metabox_title = _x( $this->p->cf[ 'meta' ][ 'title' ], 'metabox title', 'wpsso' );	// Use wpsso's text domain.
					$metabox_tab   = _x( 'Schema Place', 'metabox tab', 'wpsso-plm' );

					$text = sprintf( __( 'A "%1$s" tab can be added to the %2$s metabox on Posts, Pages, and custom post types, allowing you to select or enter place information for the webpage content (ie. street address, GPS coordinates, opening hours, etc.).', 'wpsso-plm' ), $metabox_tab, $metabox_title );

					break;

			}

			return $text;
		}

		public function filter_messages_tooltip_meta( $text, $msg_key ) {

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			if ( strpos( $msg_key, 'tooltip-meta-plm_' ) !== 0 ) {
				return $text;
			}

			switch ( $msg_key ) {

				case 'tooltip-meta-plm_place_id':

					$text = __( 'Select an existing place or enter a custom place below.', 'wpsso-plm' );

					break;
			}

			return $text;
		}
	}
}
