
jQuery( document ).bind( 'sucom_init_metabox', function( event, container_id, doing_ajax ) {

	var table_id = 'table.sucom-settings';

	if ( 'undefined' !== typeof container_id && container_id ) {

		table_id = container_id + ' ' + table_id;
	}

	/**
	 * When the Place ID is changed, update the Schema Type and Organization ID.
	 */
	jQuery( table_id + ' select#select_plm_place_id' ).show( plmPlaceSchemaType );
	jQuery( table_id + ' select#select_plm_place_id' ).change( plmPlaceSchemaType );
} );

function plmPlaceSchemaType() {

	var select_place_id = jQuery( this );
	var place_id        = select_place_id.val();

	var schema_type_linked = jQuery( 'div#schema_type_linked' );	/* May not exist. */
	var select_schema_type = jQuery( 'select#select_og_schema_type' );
	var schema_type_id     = select_schema_type.val();
	var def_schema_type_id = select_schema_type.attr( 'data-default-value' );

	if ( schema_type_linked.length ) {

		jQuery( schema_type_linked ).remove();
	}

	var schema_org_org_linked = jQuery( 'div#schema_org_org_linked' );	/* May not exist. */
	var select_schema_org_org = jQuery( 'select#select_schema_organization_org_id' );
	var schema_org_org_id     = select_schema_org_org.val();
	var def_schema_org_org_id = select_schema_org_org.attr( 'data-default-value' );

	if ( schema_org_org_linked.length ) {

		jQuery( schema_org_org_linked ).remove();
	}

	/**
	 * place_id can be 'custom', 'none', or a place ID number (including 0).
	 */
	if ( place_id === '' || place_id === 'none' ) {

		/**
		 * If previously disabled, reenable and set to the default value.
		 */
		if ( schema_type_linked.length ) {

			select_schema_type.prop( 'disabled', false );

			if ( 'undefined' !== typeof def_schema_type_id ) {

				if ( def_schema_type_id !== schema_type_id ) {

					select_schema_type.trigger( 'load_json' ).val( def_schema_type_id ).trigger( 'change' );
				}
			}
		}

		if ( schema_org_org_linked.length ) {

			select_schema_org_org.prop( 'disabled', false );

			if ( 'undefined' !== typeof def_schema_org_org_id ) {

				if ( def_schema_org_org_id !== schema_org_org_id ) {

					select_schema_org_org.trigger( 'load_json' ).val( def_schema_org_org_id ).trigger( 'change' );
				}
			}
		}

	} else {

		var place_opt_ext = '_' + place_id;

		if ( place_id === 'custom' ) {

			place_opt_ext = '';
		}

		var place_id_label = sucomAdminPageL10n._option_labels[ 'plm_place_id' ];

		var linked_to_label = sucomAdminPageL10n._linked_to_msg.replace( /%s/, place_id_label );

		/**
		 * Check the Schema Type value.
		 */
		var select_place_schema_type = jQuery( 'select#select_plm_place_schema_type' + place_opt_ext );
		var place_schema_type_id     = select_place_schema_type.val();

		select_schema_type.after( '<div id="schema_type_linked" class="dashicons dashicons-admin-links linked_to_msg" title="' + linked_to_label + '"></div>' );
		select_schema_type.prop( 'disabled', true );

		if ( place_schema_type_id !== schema_type_id ) {

			select_schema_type.trigger( 'load_json' ).val( place_schema_type_id ).trigger( 'change' );
		}

		/**
		 * Check the Schema Type value.
		 */
		select_schema_org_org.after( '<div id="schema_org_org_linked" class="dashicons dashicons-admin-links linked_to_msg" title="' + linked_to_label + '"></div>' );
		select_schema_org_org.prop( 'disabled', true );

		if ( 'none' !== schema_org_org_id ) {

			select_schema_org_org.trigger( 'load_json' ).val( 'none' ).trigger( 'change' );
		}
	}
}
