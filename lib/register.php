<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2020 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoPlmRegister' ) ) {

	class WpssoPlmRegister {

		public function __construct() {

			register_activation_hook( WPSSOPLM_FILEPATH, array( $this, 'network_activate' ) );

			register_deactivation_hook( WPSSOPLM_FILEPATH, array( $this, 'network_deactivate' ) );

			if ( is_multisite() ) {

				add_action( 'wpmu_new_blog', array( $this, 'wpmu_new_blog' ), 10, 6 );

				add_action( 'wpmu_activate_blog', array( $this, 'wpmu_activate_blog' ), 10, 5 );
			}

			/**
			 * Add-on hook priorities:
			 *
			 * 	FAQs = 10
			 * 	Organizations = 20
			 * 	Places = 30
			 */
			//add_action( 'wpsso_init_options', array( $this, 'register_taxonomy_plm_category' ), 30 );

			//add_action( 'wpsso_init_options', array( $this, 'register_post_type_place' ), 30 );
		}

		/**
		 * Fires immediately after a new site is created.
		 */
		public function wpmu_new_blog( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {

			switch_to_blog( $blog_id );

			$this->activate_plugin();

			restore_current_blog();
		}

		/**
		 * Fires immediately after a site is activated (not called when users and sites are created by a Super Admin).
		 */
		public function wpmu_activate_blog( $blog_id, $user_id, $password, $signup_title, $meta ) {

			switch_to_blog( $blog_id );

			$this->activate_plugin();

			restore_current_blog();
		}

		public function network_activate( $sitewide ) {

			self::do_multisite( $sitewide, array( $this, 'activate_plugin' ) );
		}

		public function network_deactivate( $sitewide ) {

			self::do_multisite( $sitewide, array( $this, 'deactivate_plugin' ) );
		}

		/**
		 * uninstall.php defines constants before calling network_uninstall().
		 */
		public static function network_uninstall() {

			$sitewide = true;

			/**
			 * Uninstall from the individual blogs first.
			 */
			self::do_multisite( $sitewide, array( __CLASS__, 'uninstall_plugin' ) );
		}

		private static function do_multisite( $sitewide, $method, $args = array() ) {

			if ( is_multisite() && $sitewide ) {

				global $wpdb;

				$db_query = 'SELECT blog_id FROM '.$wpdb->blogs;
				$blog_ids = $wpdb->get_col( $db_query );

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );

					call_user_func_array( $method, array( $args ) );
				}

				restore_current_blog();

			} else {
				call_user_func_array( $method, array( $args ) );
			}
		}

		private function activate_plugin() {

			if ( class_exists( 'Wpsso' ) ) {

				/**
				 * Register plugin install, activation, update times.
				 */
				if ( class_exists( 'WpssoUtilReg' ) ) {	// Since WPSSO v6.13.1.

					$version = WpssoPlmConfig::$cf[ 'plugin' ][ 'wpssoplm' ][ 'version' ];

					WpssoUtilReg::update_ext_version( 'wpssoplm', $version );
				}

				//$this->register_taxonomy_plm_category();

				//$this->register_post_type_place();

				//flush_rewrite_rules();
			}
		}

		private function deactivate_plugin() {

			//unregister_taxonomy( WPSSOPLM_CATEGORY_TAXONOMY );

			//unregister_post_type( WPSSOPLM_PLACE_POST_TYPE );

			//flush_rewrite_rules();
		}

		private static function uninstall_plugin() {
		}

		public function register_taxonomy_plm_category() {

			$labels = array(
				'name'                       => __( 'Categories', 'wpsso-plm' ),
				'singular_name'              => __( 'Category', 'wpsso-plm' ),
				'menu_name'                  => _x( 'Categories', 'Admin menu name', 'wpsso-plm' ),
				'all_items'                  => __( 'All Categories', 'wpsso-plm' ),
				'edit_item'                  => __( 'Edit Category', 'wpsso-plm' ),
				'view_item'                  => __( 'View Category', 'wpsso-plm' ),
				'update_item'                => __( 'Update Category', 'wpsso-plm' ),
				'add_new_item'               => __( 'Add New Category', 'wpsso-plm' ),
				'new_item_name'              => __( 'New Category Name', 'wpsso-plm' ),
				'parent_item'                => __( 'Parent Category', 'wpsso-plm' ),
				'parent_item_colon'          => __( 'Parent Category:', 'wpsso-plm' ),
				'search_items'               => __( 'Search Categories', 'wpsso-plm' ),
				'popular_items'              => __( 'Popular Categories', 'wpsso-plm' ),
				'separate_items_with_commas' => __( 'Separate categories with commas', 'wpsso-plm' ),
				'add_or_remove_items'        => __( 'Add or remove categories', 'wpsso-plm' ),
				'choose_from_most_used'      => __( 'Choose from the most used', 'wpsso-plm' ),
				'not_found'                  => __( 'No categories found.', 'wpsso-plm' ),
				'back_to_items'              => __( '← Back to categories', 'wpsso-plm' ),
			);

			$args = array(
				'label'              => _x( 'Categories', 'Taxonomy label', 'wpsso-plm' ),
				'labels'             => $labels,
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_nav_menus'  => true,
				'show_in_rest'       => true,
				'show_tagcloud'      => false,
				'show_in_quick_edit' => true,
				'show_admin_column'  => true,
				'description'        => _x( 'Categories for Places', 'Taxonomy description', 'wpsso-plm' ),
				'hierarchical'       => true,
			);

			register_taxonomy( WPSSOPLM_CATEGORY_TAXONOMY, array( WPSSOPLM_PLACE_POST_TYPE ), $args );
		
		}

		public function register_post_type_place() {

			$labels = array(
				'name'                     => __( 'Places', 'Post type general name', 'wpsso-plm' ),
				'singular_name'            => __( 'Place', 'Post type singular name', 'wpsso-plm' ),
				'add_new'                  => __( 'Add New', 'wpsso-plm' ),
				'add_new_item'             => __( 'Add New Place', 'wpsso-plm' ),
				'edit_item'                => __( 'Edit Place', 'wpsso-plm' ),
				'new_item'                 => __( 'New Place', 'wpsso-plm' ),
				'view_item'                => __( 'View Place', 'wpsso-plm' ),
				'view_items'               => __( 'View Places', 'wpsso-plm' ),
				'search_items'             => __( 'Search Places', 'wpsso-plm' ),
				'not_found'                => __( 'No places found', 'wpsso-plm' ),
				'not_found_in_trash'       => __( 'No places found in Trash', 'wpsso-plm' ),
				'parent_item_colon'        => __( 'Parent Place:', 'wpsso-plm' ),
				'all_items'                => __( 'All Places', 'wpsso-plm' ),
				'archives'                 => __( 'Place Archives', 'wpsso-plm' ),
				'attributes'               => __( 'Place Attributes', 'wpsso-plm' ),
				'insert_into_item'         => __( 'Insert into place', 'wpsso-plm' ),
				'uploaded_to_this_item'    => __( 'Uploaded to this place', 'wpsso-plm' ),
				'featured_image'           => __( 'Place Image', 'wpsso-plm' ),
				'set_featured_image'       => __( 'Set place image', 'wpsso-plm' ),
				'remove_featured_image'    => __( 'Remove place image', 'wpsso-plm' ),
				'use_featured_image'       => __( 'Use as place image', 'wpsso-plm' ),
				'menu_name'                => _x( 'Places', 'Admin menu name', 'wpsso-plm' ),
				'filter_items_list'        => __( 'Filter places', 'wpsso-plm' ),
				'items_list_navigation'    => __( 'Places list navigation', 'wpsso-plm' ),
				'items_list'               => __( 'Places list', 'wpsso-plm' ),
				'name_admin_bar'           => _x( 'Place', 'Admin bar name', 'wpsso-plm' ),
				'item_published'	   => __( 'Place published.', 'wpsso-plm' ),
				'item_published_privately' => __( 'Place published privately.', 'wpsso-plm' ),
				'item_reverted_to_draft'   => __( 'Place reverted to draft.', 'wpsso-plm' ),
				'item_scheduled'           => __( 'Place scheduled.', 'wpsso-plm' ),
				'item_updated'             => __( 'Place updated.', 'wpsso-plm' ),
			);

			$args = array(
				'label'                 => _x( 'Place', 'Post type label', 'wpsso-plm' ),
				'labels'                => $labels,
				'description'           => _x( 'Location, place, or venue', 'Post type description', 'wpsso-plm' ),
				'public'                => true,
				'exclude_from_search'   => false,
				'publicly_queryable'    => true,
				'show_ui'               => true,
				'show_in_nav_menus'     => true,
				'show_in_menu'          => true,
				'show_in_admin_bar'     => true,
				'menu_position'         => 20,
				'menu_icon'             => 'dashicons-location',
				'capability_type'       => 'page',
				'hierarchical'          => false,
				'supports'              => array(
					'title',
					'editor',
					'author',
					'thumbnail',
					'excerpt',
					'trackbacks',
					'comments',
					'revisions',
					'page-attributes',
				),
				'taxonomies'            => array( WPSSOPLM_CATEGORY_TAXONOMY ),
				'has_archive'           => 'places',
				'can_export'            => true,
				'show_in_rest'          => true,
			);

			register_post_type( WPSSOPLM_PLACE_POST_TYPE, $args );
		}
	}
}
