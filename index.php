<?php
/**
 * Plugin Name: Mannheim Under Construction
 * Description: Provides a map with audio stations
 * Requires at least: 4.9
 * Requires PHP: 5.4
 * Author: Daniel Winzen
 * Author URI: https://danwin1210.de
 * Text Domain: mannheim-under-construction
 * Domain Path: /languages
 * Update URI: false
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

class Mannheim_Under_Constrcution
{
	public function __construct()
	{
		$this->register_hooks();
	}

	public static function get_instance() : self
	{
		static $instance = null;
		if($instance instanceof self){
			return $instance;
		}
		$instance = new self();
		return $instance;
	}

	private function register_hooks() : void
	{
		add_action( 'init', function () {
			register_post_type( 'audio-station', [
				'label' => __( 'Audio stations', 'mannheim-under-construction' ),
				'labels' => [
					'name' => _x( 'Audio stations', 'audio-station', 'mannheim-under-construction' ),
					'singular_name' => _x( 'Audio station', 'audio-station', 'mannheim-under-construction' ),
					'add_new' => _x( 'Add new', 'audio-station', 'mannheim-under-construction' ),
					'add_new_item' => __( 'Add new audio station', 'mannheim-under-construction' ),
					'edit_item' => __( 'Edit audio station', 'mannheim-under-construction' ),
					'new_item' => __( 'New audio station', 'mannheim-under-construction' ),
					'view_item' => __( 'View audio station', 'mannheim-under-construction' ),
					'view_items' => __( 'View audio stations', 'mannheim-under-construction' ),
					'search_items' => __( 'Search audio stations', 'mannheim-under-construction' ),
					'not_found' => __( 'No audio stations found.', 'mannheim-under-construction' ),
					'not_found_in_trash' => __( 'No audio stations found in Trash.', 'mannheim-under-construction' ),
					'all_items' => __( 'All audio stations', 'mannheim-under-construction' ),
					'archives' => __( 'Audio station Archives', 'mannheim-under-construction' ),
					'attributes' => __( 'Audio station Attributes', 'mannheim-under-construction' ),
					'insert_into_item' => __( 'Insert into audio station', 'mannheim-under-construction' ),
					'uploaded_to_this_item' => __( 'Uploaded to this audio station', 'mannheim-under-construction' ),
					'featured_image' => _x( 'Featured image', 'audio-station', 'mannheim-under-construction' ),
					'set_featured_image' => _x( 'Set featured image', 'audio-station', 'mannheim-under-construction' ),
					'remove_featured_image' => _x( 'Remove featured image', 'audio-station', 'mannheim-under-construction' ),
					'use_featured_image' => _x( 'Use as featured image', 'audio-station', 'mannheim-under-construction' ),
					'filter_items_list' => __( 'Filter audio stations list', 'mannheim-under-construction' ),
					'filter_by_date' => __( 'Filter by date', 'mannheim-under-construction' ),
					'items_list_navigation' => __( 'Audio stations list navigation', 'mannheim-under-construction' ),
					'items_list' => __( 'Audio stations list', 'mannheim-under-construction' ),
					'item_published' => __( 'Audio station published.', 'mannheim-under-construction' ),
					'item_published_privately' => __( 'Audio station published privately.', 'mannheim-under-construction' ),
					'item_reverted_to_draft' => __( 'Audio station reverted to draft.', 'mannheim-under-construction' ),
					'item_scheduled' => __( 'Audio station scheduled.', 'mannheim-under-construction' ),
					'item_updated' => __( 'Audio station updated.', 'mannheim-under-construction' ),
				],
				'description' => __( 'Audio stations that will be shown on a map', 'mannheim-under-construction' ),
				'public' => false,
				'hierarchical' => false,
				'publicly_queryable' => false,
				'show_ui' => true,
				'show_in_rest' => true,
				'menu_icon' => 'dashicons-format-audio',
				'supports' => [
					'title',
					'editor',
					'author',
					'thumbnail',
				],
				'register_meta_box_cb' => [__CLASS__, 'register_meta_box'],
				'taxonomies' => [
					'post_tag',
				],
				'has_archive' => false,
				'rewrite' => false,
				'query_var' => false,
				'delete_with_user' => false,
			] );

			register_post_type( 'audio-walk', [
				'label' => __( 'Audio walks', 'mannheim-under-construction' ),
				'labels' => [
					'name' => _x( 'Audio walks', 'audio-walk', 'mannheim-under-construction' ),
					'singular_name' => _x( 'Audio walk', 'audio-walk', 'mannheim-under-construction' ),
					'add_new' => _x( 'Add new', 'audio-walk', 'mannheim-under-construction' ),
					'add_new_item' => __( 'Add new audio walk', 'mannheim-under-construction' ),
					'edit_item' => __( 'Edit audio walk', 'mannheim-under-construction' ),
					'new_item' => __( 'New audio walk', 'mannheim-under-construction' ),
					'view_item' => __( 'View audio walk', 'mannheim-under-construction' ),
					'view_items' => __( 'View audio walk', 'mannheim-under-construction' ),
					'search_items' => __( 'Search audio walks', 'mannheim-under-construction' ),
					'not_found' => __( 'No audio walks found.', 'mannheim-under-construction' ),
					'not_found_in_trash' => __( 'No audio walks found in Trash.', 'mannheim-under-construction' ),
					'all_items' => __( 'All audio walks', 'mannheim-under-construction' ),
					'archives' => __( 'Audio walk Archives', 'mannheim-under-construction' ),
					'attributes' => __( 'Audio walk Attributes', 'mannheim-under-construction' ),
					'insert_into_item' => __( 'Insert into audio walk', 'mannheim-under-construction' ),
					'uploaded_to_this_item' => __( 'Uploaded to this audio walk', 'mannheim-under-construction' ),
					'featured_image' => _x( 'Featured image', 'audio-walk', 'mannheim-under-construction' ),
					'set_featured_image' => _x( 'Set featured image', 'audio-walk', 'mannheim-under-construction' ),
					'remove_featured_image' => _x( 'Remove featured image', 'audio-walk', 'mannheim-under-construction' ),
					'use_featured_image' => _x( 'Use as featured image', 'audio-walk', 'mannheim-under-construction' ),
					'filter_items_list' => __( 'Filter audio walks list', 'mannheim-under-construction' ),
					'filter_by_date' => __( 'Filter by date', 'mannheim-under-construction' ),
					'items_list_navigation' => __( 'Audio walks list navigation', 'mannheim-under-construction' ),
					'items_list' => __( 'Audio walks list', 'mannheim-under-construction' ),
					'item_published' => __( 'Audio walk published.', 'mannheim-under-construction' ),
					'item_published_privately' => __( 'Audio walk published privately.', 'mannheim-under-construction' ),
					'item_reverted_to_draft' => __( 'Audio walk reverted to draft.', 'mannheim-under-construction' ),
					'item_scheduled' => __( 'Audio walk scheduled.', 'mannheim-under-construction' ),
					'item_updated' => __( 'Audio walk updated.', 'mannheim-under-construction' ),
				],
				'description' => __( 'Audio walks that will be shown on a map', 'mannheim-under-construction' ),
				'public' => false,
				'hierarchical' => false,
				'publicly_queryable' => false,
				'show_ui' => true,
				'show_in_rest' => true,
				'menu_icon' => 'dashicons-buddicons-activity',
				'supports' => [
					'title',
					'editor',
					'author',
					'thumbnail',
				],
				'register_meta_box_cb' => [__CLASS__, 'register_meta_box'],
				'has_archive' => false,
				'rewrite' => false,
				'query_var' => false,
				'delete_with_user' => false,
			] );

			 register_taxonomy('length', 'audio-station', [
				'label' => __('Lengths', 'mannheim-under-construction'),
				'description' => __('Length of an audio station', 'mannheim-under-construction'),
				'public' => true,
				'publicly_queryable' => false,
				'hierarchical' => true,
				'rewrite' => false,
				'show_in_rest' => true,
			]);

			register_taxonomy('location', 'audio-station', [
				'label' => __('Locations', 'mannheim-under-construction'),
				'description' => __('Location of an audio station', 'mannheim-under-construction'),
				'public' => true,
				'publicly_queryable' => false,
				'hierarchical' => true,
				'rewrite' => false,
				'show_in_rest' => true,
			]);

			register_taxonomy('producer', 'audio-station', [
				'label' => __('Producers', 'mannheim-under-construction'),
				'description' => __('Producer of an audio station', 'mannheim-under-construction'),
				'public' => true,
				'publicly_queryable' => false,
				'hierarchical' => true,
				'rewrite' => false,
				'show_in_rest' => true,
			]);

			register_taxonomy('production-date', 'audio-station', [
				'label' => __('Production dates', 'mannheim-under-construction'),
				'description' => __('Production date of an audio station', 'mannheim-under-construction'),
				'public' => true,
				'publicly_queryable' => false,
				'hierarchical' => true,
				'rewrite' => false,
				'show_in_rest' => true,
			]);

			register_taxonomy('post-type', 'audio-station', [
				'label' => __('Types', 'mannheim-under-construction'),
				'description' => __('Type of audio station', 'mannheim-under-construction'),
				'public' => true,
				'publicly_queryable' => false,
				'hierarchical' => true,
				'rewrite' => false,
				'show_in_rest' => true,
			]);
		} );

		add_action( 'plugins_loaded', function () {
			load_plugin_textdomain( 'mannheim-under-construction', FALSE, basename( __DIR__ ) . '/languages/' );
		} );

		add_filter( 'plugin_action_links', function ( $links, $file ) {
			if ( $file === 'under-construction/index.php' && current_user_can( 'manage_options' ) ) {
				$links = (array) $links;
				$links[] = sprintf( '<a href="%s">%s</a>', admin_url( 'options-general.php?page=mannheim-under-construction' ), esc_html__( 'Settings', 'mannheim-under-construction' ) );
			}
			return $links;
		}, 10, 2 );

		add_action( 'save_post', function( $post_id ) {
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
			if ( $parent_id = wp_is_post_revision( $post_id ) ) {
				$post_id = $parent_id;
			}
			if(get_post_type($post_id) === 'audio-station') {
				$textarea_fields = [
					'mannheim_under_construction_credits',
				];
				$fields = [
					'mannheim_under_construction_ogg',
					'mannheim_under_construction_aac',
					'mannheim_under_construction_location',
					'mannheim_under_construction_location_lat',
					'mannheim_under_construction_location_lng',
					'mannheim_under_construction_location_hidden',
					'mannheim_under_construction_location_2',
					'mannheim_under_construction_campaign_type',
				];
				foreach ( $fields as $field ) {
					if ( array_key_exists( $field, $_POST ) ) {
						update_post_meta( $post_id, $field, sanitize_text_field( $_POST[ $field ] ) );
					}
				}
				foreach ( $textarea_fields as $field ) {
					if ( array_key_exists( $field, $_POST ) ) {
						update_post_meta( $post_id, $field, sanitize_textarea_field( $_POST[ $field ] ) );
					}
				}
				if(is_array($_POST['mannheim_under_construction_accordions'])) {
					$accordions = [];
					foreach ( $_POST[ 'mannheim_under_construction_accordions' ] as $accordion ) {
						if ( isset( $accordion[ 'title' ] ) && ! empty( $accordion[ 'description' ] ) ) {
							$accordions [] = $accordion;
						}
					}
					update_post_meta( $post_id, 'mannheim_under_construction_accordions', $accordions );
				}
				if(is_array($_POST['mannheim_under_construction_audio_updates'])) {
					$updates = [];
					foreach ( $_POST[ 'mannheim_under_construction_audio_updates' ] as $update ) {
						if ( ! empty( $update[ 'audio_id' ] ) ) {
							$updates [] = $update;
						}
					}
					update_post_meta( $post_id, 'mannheim_under_construction_audio_updates', $updates );
				}
            } elseif(get_post_type($post_id) === 'audio-walk') {
				$fields = [
					'mannheim_under_construction_location_lat',
					'mannheim_under_construction_location_lng',
                ];
				foreach ( $fields as $field ) {
					if ( array_key_exists( $field, $_POST ) ) {
						update_post_meta( $post_id, $field, sanitize_text_field( $_POST[ $field ] ) );
					}
				}
                if(is_array($_POST['mannheim_under_construction_stations'])){
                    $stations = [];
	                foreach($_POST['mannheim_under_construction_stations'] as $station){
		                if(isset($station['title']) && !empty($station['audio_id'])){
                            $exists = get_posts([
                                'post_type' => 'audio-station',
                                'posts_per_page' => 1,
                                'p' => $station['audio_id'],
                            ])[0] ?? false;
                            if($exists){
                                $stations []= $station;
                            }
		                }
	                }
	                update_post_meta( $post_id, 'mannheim_under_construction_stations', $stations );
                }
                if(is_array($_POST['mannheim_under_construction_intros'])){
                    $intros = [];
	                foreach($_POST['mannheim_under_construction_intros'] as $station){
		                if(isset($station['title']) && !empty($station['audio_id'])){
                            $exists = get_posts([
                                'post_type' => 'audio-station',
                                'posts_per_page' => 1,
                                'p' => $station['audio_id'],
                            ])[0] ?? false;
                            if($exists){
                                $intros []= $station;
                            }
		                }
	                }
	                update_post_meta( $post_id, 'mannheim_under_construction_intros', $intros );
                }
			}
		} );

		add_action( 'admin_init', function () {
			add_settings_section(
				'mannheim_under_construction_settings_campaign',
				__( 'Campaign', 'mannheim-under-construction' ),
				function () {},
				'mannheim-under-construction'
			);

			add_settings_field(
				'mannheim_under_construction_campaign',
				__( 'Enable campaign', 'mannheim-under-construction' ),
				function () {
					echo '<input type="checkbox" name="mannheim_under_construction_campaign" class="regular-text code" value="1" ' . ( get_option( 'mannheim_under_construction_campaign', 0 ) ? 'checked' : '' ) . '>';
				},
				'mannheim-under-construction',
				'mannheim_under_construction_settings_campaign'
			);

			add_settings_field(
				'mannheim_under_construction_campaign_headline',
				__( 'Campaign Headline', 'mannheim-under-construction' ),
				function () {
					echo '<input type="text" name="mannheim_under_construction_campaign_headline" class="regular-text code" value="' . esc_attr( get_option( 'mannheim_under_construction_campaign_headline' ) ) . '">';
				},
				'mannheim-under-construction',
				'mannheim_under_construction_settings_campaign'
			);

			add_settings_field(
				'mannheim_under_construction_campaign_text',
				__( 'Campaign Text', 'mannheim-under-construction' ),
				function () {
					echo '<textarea name="mannheim_under_construction_campaign_text" class="regular-text code">' . esc_html( get_option( 'mannheim_under_construction_campaign_text' ) ) . '</textarea>';
				},
				'mannheim-under-construction',
				'mannheim_under_construction_settings_campaign'
			);

			add_settings_section(
				'mannheim_under_construction_settings_popup',
				__( 'Popup', 'mannheim-under-construction' ),
				function () {},
				'mannheim-under-construction'
			);

			add_settings_field(
				'mannheim_under_construction_popup_show',
				__( 'Show popup', 'mannheim-under-construction' ),
				function () {
					echo '<input type="checkbox" name="mannheim_under_construction_popup_show" class="regular-text code" value="1" ' . ( get_option( 'mannheim_under_construction_popup_show', 0 ) ? 'checked' : '' ) . '>';
				},
				'mannheim-under-construction',
				'mannheim_under_construction_settings_popup'
			);

			add_settings_field(
				'mannheim_under_construction_popup_headline',
				__( 'Popup Headline', 'mannheim-under-construction' ),
				function () {
					echo '<input type="text" name="mannheim_under_construction_popup_headline" class="regular-text code" value="' . esc_attr( get_option( 'mannheim_under_construction_popup_headline' ) ) . '">';
				},
				'mannheim-under-construction',
				'mannheim_under_construction_settings_popup'
			);

			add_settings_field(
				'mannheim_under_construction_popup_text',
				__( 'Popup Text', 'mannheim-under-construction' ),
				function () {
					echo '<textarea name="mannheim_under_construction_popup_text" class="regular-text code">' . esc_html( get_option( 'mannheim_under_construction_popup_text' ) ) . '</textarea>';
				},
				'mannheim-under-construction',
				'mannheim_under_construction_settings_popup'
			);

			register_setting( 'mannheim-under-construction', 'mannheim_under_construction_popup_show', [ 'type' => 'bool' ] );
			register_setting( 'mannheim-under-construction', 'mannheim_under_construction_campaign', [ 'type' => 'bool' ] );
			register_setting( 'mannheim-under-construction', 'mannheim_under_construction_campaign_headline', [ 'type' => 'string' ] );
			register_setting( 'mannheim-under-construction', 'mannheim_under_construction_campaign_text', [ 'type' => 'string' ] );
			register_setting( 'mannheim-under-construction', 'mannheim_under_construction_popup_text', [ 'type' => 'string' ] );
			register_setting( 'mannheim-under-construction', 'mannheim_under_construction_popup_headline', [ 'type' => 'string' ] );
		} );

		add_action( 'admin_menu', function () {
			add_options_page( __( 'Mannheim Under Construction', 'mannheim-under-construction' ), __( 'Mannheim Under Construction', 'mannheim-under-construction' ), 'manage_options', 'mannheim-under-construction', [ __CLASS__, 'options_page' ] );
		} );


		add_action( 'admin_enqueue_scripts', function() {
			global $typenow;
			if( in_array($typenow, ['audio-station', 'audio-walk'], true) ) {
				wp_enqueue_media();
                $walk_stations = [];
                $walk_intros = [];
				$accordions = [];
				$updates = [];
                $is_walk = $typenow === 'audio-walk';
				$audios = get_posts([
					'post_type' => 'audio-station',
					'posts_per_page' => -1,
					'orderby' => ['title' => 'ASC'],
				]);
				if(is_array($audios)) {
					foreach ( $audios as $audio ) {
						$audios_titles []= ['title' => esc_attr($audio->post_title), 'id' => $audio->ID];
					}
				}
				if($is_walk){
                    if(get_the_ID()) {
	                    $stations = get_post_meta( get_the_ID(), 'mannheim_under_construction_stations', true );
                        if(is_array($stations)){
                            foreach($stations as $station){
                                $walk_stations []= ['title' => esc_attr($station['title']), 'audio_id' => $station['audio_id']];
                            }
                        }
	                    $intros = get_post_meta( get_the_ID(), 'mannheim_under_construction_intros', true );
	                    if(is_array($intros)){
		                    foreach($intros as $intro){
			                    $walk_intros []= ['title' => esc_attr($intro['title']), 'audio_id' => $intro['audio_id']];
		                    }
	                    }
                    }
				} else {
					if ( get_the_ID() ) {
						$track_accordions = get_post_meta( get_the_ID(), 'mannheim_under_construction_accordions', true );
						if ( is_array( $track_accordions ) ) {
							foreach ( $track_accordions as $accordion ) {
								$accordions [] = [ 'title'    => esc_attr( $accordion[ 'title' ] ),
								                   'description' => esc_html($accordion[ 'description' ])
								];
							}
						}
						$audio_updates = get_post_meta( get_the_ID(), 'mannheim_under_construction_audio_updates', true );
						if ( is_array( $audio_updates ) ) {
							foreach ( $audio_updates as $update ) {
								$updates [] = [
									'audio_id' => $update[ 'audio_id' ],
								];
							}
						}
					}
				}
                wp_enqueue_script( 'mannheim-under-construction-leaflet', plugins_url( 'leaflet/leaflet.js' , __FILE__ ), [], '1.9.3' );
				wp_enqueue_style( 'mannheim-under-construction-leaflet', plugins_url( 'leaflet/leaflet.css' , __FILE__ ), [], '1.9.3' );
				wp_enqueue_style( 'mannheim-under-construction-admin', plugins_url( 'assets/css/admin.css' , __FILE__ ), [], substr(md5_file( __DIR__ . '/assets/css/admin.css' ), 20));
				wp_enqueue_script( 'mannheim-under-construction-admin', plugins_url( 'assets/js/admin.js' , __FILE__ ), ['mannheim-under-construction-leaflet'], substr(md5_file( __DIR__ . '/assets/js/admin.js' ), 20));
				wp_localize_script( 'mannheim-under-construction-admin', 'mannheim_under_construction_admin',
					[
						'title' => __( 'Choose or Upload Media', 'mannheim-under-construction' ),
						'button' => __( 'Use this media', 'mannheim-under-construction' ),
						'desired_location' => __( 'Desired location', 'mannheim-under-construction' ),
						'audio_icon_url' => plugins_url( 'assets/img/uc_icon_pin.svg', __FILE__ ),
                        'is_walk' => $is_walk,
                        'walk' => [
                            'stations' => $walk_stations,
                            'intros' => $walk_intros,
                        ],
						'audios_titles' => $audios_titles,
                        'accordions' => $accordions,
                        'updates' => $updates,
						'upload_button_aac' => esc_html__('Upload AAC-Audio file', 'mannheim-under-construction'),
						'upload_button_ogg' => esc_html__('Upload OGG-Audio file', 'mannheim-under-construction'),
					]
				);
			}
		} );

		add_action( 'wp_enqueue_scripts', function() {
			if(is_page('map')) {
				// remove anything added by wp
				$wp_scripts = wp_scripts();
				$wp_styles  = wp_styles();

				foreach ( $wp_scripts->registered as $wp_script ) {
					wp_deregister_script( $wp_script->handle );
				}

				foreach ( $wp_styles->registered as $wp_style ) {
					wp_deregister_style( $wp_style->handle );
				}
				wp_deregister_script( 'wp-embed' );
				wp_enqueue_style( 'mannheim-under-construction-leaflet', plugins_url( 'leaflet/leaflet.css', __FILE__ ), [], '1.9.3' );
				wp_enqueue_script( 'mannheim-under-construction-leaflet', plugins_url( 'leaflet/leaflet.js', __FILE__ ), [], '1.9.3', true );
				wp_enqueue_style( 'mannheim-under-construction-sidebar-v2', plugins_url( 'assets/css/leaflet-sidebar-customized.css', __FILE__ ), [], substr( md5_file( __DIR__ . '/assets/css/leaflet-sidebar-customized.css' ), 20 ) );
				wp_enqueue_script( 'mannheim-under-construction-sidebar-v2', plugins_url( 'assets/js/leaflet-sidebar-customized.js', __FILE__ ), [], substr( md5_file( __DIR__ . '/assets/js/leaflet-sidebar-customized.js' ), 20 ), true );
				wp_enqueue_style( 'mannheim-under-construction-leaflet-markercluster', plugins_url( 'Leaflet.markercluster/dist/MarkerCluster.css', __FILE__ ), [], '1.5.3' );
				wp_enqueue_style( 'mannheim-under-construction-leaflet-markercluster-default', plugins_url( 'Leaflet.markercluster/dist/MarkerCluster.Default.css', __FILE__ ), [], '1.5.3' );
				wp_enqueue_script( 'mannheim-under-construction-leaflet-markercluster', plugins_url( 'Leaflet.markercluster/dist/leaflet.markercluster.js', __FILE__ ), [], '1.5.3', true );
				wp_enqueue_style( 'mannheim-under-construction', plugins_url( 'assets/css/main.css', __FILE__ ), [], substr( md5_file( __DIR__ . '/assets/css/main.css' ), 20 ) );
				wp_enqueue_script( 'mannheim-under-construction', plugins_url( 'assets/js/main.js', __FILE__ ), [ 'mannheim-under-construction-leaflet', 'mannheim-under-construction-sidebar-v2', 'mannheim-under-construction-leaflet-markercluster' ], substr( md5_file( __DIR__ . '/assets/js/main.js' ), 20 ), true );

				$map_data = [];
				$initial_audio = 0;
				$initial_walk = 0;
				$campaign_enabled = get_option( 'mannheim_under_construction_campaign', 0 );
				$audio_posts = get_posts([
					'post_type' => 'audio-station',
					'posts_per_page' => -1,
					'fields' => 'ids',
				]);
				$audio_walks = get_posts([
					'post_type' => 'audio-walk',
					'posts_per_page' => -1,
					'fields' => 'ids',
				]);
				$tag_data = get_tags([
					'fields' => 'id=>name',
					'hide_empty' => true,
					'orderby' => 'name',
				]);
                if(is_array($audio_posts)) {
	                foreach ( $audio_posts as $post_id ) {
		                $ogg_id          = get_post_meta( $post_id, 'mannheim_under_construction_ogg', true );
		                $aac_id          = get_post_meta( $post_id, 'mannheim_under_construction_aac', true );
		                $ogg_meta        = wp_get_attachment_metadata( $ogg_id );
		                $aac_meta        = wp_get_attachment_metadata( $aac_id );
		                $length          = '';
		                $length_readable = '';
		                $tags            = get_tags( [
			                'object_ids' => $post_id,
			                'fields'     => 'ids',
		                ] );
		                if ( ! is_array( $tags ) ) {
			                $tags = [];
		                }
						$production_date = '';
		                $production_dates = get_terms( [
			                'object_ids' => $post_id,
			                'taxonomy'   => 'production-date',
		                ] );
		                if(is_array($production_dates) && !empty($production_dates)) {
							$production_date = __('Production date: ', 'mannheim-under-construction');
							foreach ($production_dates as $date){
								$production_date .= $date->name . ', ';
							}
							$production_date = rtrim($production_date, ', ');
		                }
		                if ( isset( $ogg_meta[ 'length_formatted' ] ) ) {
			                $length          = $ogg_meta[ 'length_formatted' ];
			                $length_readable = human_readable_duration( $length );
		                } elseif ( isset( $aac_meta[ 'length_formatted' ] ) ) {
			                $length          = $aac_meta[ 'length_formatted' ];
			                $length_readable = human_readable_duration( $length );
		                }
						$updates = [];
						$audio_updates = get_post_meta( $post_id, 'mannheim_under_construction_audio_updates', true );
						foreach($audio_updates as $update){
							$updates []= [
								'audio_id' => $update['audio_id'],
							];
						}
						$campaign_type = '';
						$campaign_title = '';
						if($campaign_enabled){
							$campaign_type = get_post_meta( $post_id, 'mannheim_under_construction_campaign_type', true );
							if($campaign_type === 'working'){
								$campaign_title = __('Working', 'mannheim-under-construction');
							} elseif($campaign_type === 'living'){
								$campaign_title = __('Living', 'mannheim-under-construction');
							} elseif($campaign_type === 'climate'){
								$campaign_title = __('Climate', 'mannheim-under-construction');
							}
						}
		                $map_data [] = [
			                'id'              => $post_id,
			                'lat'             => get_post_meta( $post_id, 'mannheim_under_construction_location_lat', true ),
			                'lng'             => get_post_meta( $post_id, 'mannheim_under_construction_location_lng', true ),
			                'location'        => esc_html( get_post_meta( $post_id, 'mannheim_under_construction_location', true ) ),
			                'location_2'      => esc_html( get_post_meta( $post_id, 'mannheim_under_construction_location_2', true ) ),
			                'accordions'      => get_post_meta( $post_id, 'mannheim_under_construction_accordions', true ),
			                'updates'         => $updates,
			                'credits'         => apply_filters( 'the_content', ( get_post_meta( $post_id, 'mannheim_under_construction_credits', true ) ) ),
			                'title'           => esc_html( get_the_title( $post_id ) ),
			                'description'     => apply_filters( 'the_content', get_the_content( null, false, $post_id ) ),
			                'ogg'             => wp_get_attachment_url( $ogg_id ),
			                'ogg_mime'        => get_post_mime_type( $ogg_id ),
			                'aac'             => wp_get_attachment_url( $aac_id ),
			                'aac_mime'        => get_post_mime_type( $aac_id ),
			                'length'          => $length,
			                'length_readable' => $length_readable,
			                'tags'            => $tags,
			                'production_date' => $production_date,
                            'thumbnail'       => get_the_post_thumbnail_url( $post_id, 'medium' ),
                            'hidden'          => intval(get_post_meta( $post_id, 'mannheim_under_construction_location_hidden', true )),
							'campaign_type'   => $campaign_type,
							'campaign_title'  => $campaign_title,
		                ];
	                }
                    do{
                        $initial_audio = $map_data[array_rand($map_data)];
                    }while($initial_audio['hidden']);
	                $initial_audio = $initial_audio['id'];
                }
                $walk_data = [];
				if(is_array($audio_walks)){
					foreach($audio_walks as $audio_walk){
						$walk_data []= [
                            'id' => $audio_walk,
                            'lat' => get_post_meta( $audio_walk, 'mannheim_under_construction_location_lat', true ),
                            'lng' => get_post_meta( $audio_walk, 'mannheim_under_construction_location_lng', true ),
                            'stations' => get_post_meta( $audio_walk, 'mannheim_under_construction_stations', true ),
                            'intros' => get_post_meta( $audio_walk, 'mannheim_under_construction_intros', true ),
                            'title' => get_the_title( $audio_walk ),
                            'description' => get_the_content( null, false, $audio_walk ),
                            'thumbnail' => get_the_post_thumbnail_url( $audio_walk, 'medium' ),
                        ];
					}
				}
				$load_initial_only = true;
				if(!empty($_GET['audio_id'])){
					foreach ($map_data as $audio){
						if($audio['id'] === (int) $_GET['audio_id']){
							$initial_audio = $audio['id'];
							$load_initial_only = false;
							break;
						}
					}
				}
				if(!empty($_GET['walk_id'])){
					foreach ($walk_data as $walk){
						if($walk['id'] === (int) $_GET['walk_id']){
							$initial_walk = $walk['id'];
							break;
						}
					}
				}
				wp_localize_script( 'mannheim-under-construction', 'mannheim_under_construction',
					[
						'audio_icon_url' => $campaign_enabled ? plugins_url( 'assets/img/uc_icon_loc_cy.svg', __FILE__ ) : plugins_url( 'assets/img/uc_icon_pin.svg', __FILE__ ),
						'audio_icon_url_bw' => $campaign_enabled ? plugins_url( 'assets/img/uc_icon_loc_cy.svg', __FILE__ ) : plugins_url( 'assets/img/uc_icon_pin_hover.svg', __FILE__ ),
						'working_icon_url' => plugins_url( 'assets/img/uc_icon_arbeit.svg', __FILE__ ),
						'working_icon_url_bw' => plugins_url( 'assets/img/uc_icon_arbeit.svg', __FILE__ ),
						'working_bg_url' => plugins_url( 'assets/img/mauc_a_muster_blk.svg', __FILE__ ),
						'living_icon_url' => plugins_url( 'assets/img/uc_icon_wohnen.svg', __FILE__ ),
						'living_icon_url_bw' => plugins_url( 'assets/img/uc_icon_wohnen.svg', __FILE__ ),
						'living_bg_url' => plugins_url( 'assets/img/mauc_w_muster_blk.svg', __FILE__ ),
						'climate_icon_url' => plugins_url( 'assets/img/uc_icon_klima.svg', __FILE__ ),
						'climate_icon_url_bw' => plugins_url( 'assets/img/uc_icon_klima.svg', __FILE__ ),
						'climate_bg_url' => plugins_url( 'assets/img/mauc_k_muster_blk.svg', __FILE__ ),
						'ajax_url' => admin_url( 'admin-ajax.php' ),
						'search_error_message' => esc_html__('Oops, an error occurred while loading your search results. Please try again', 'mannheim-under-construction'),
						'zoom_in_title' => esc_html__('Zoom in', 'mannheim-under-construction'),
						'zoom_out_title' => esc_html__('Zoom out', 'mannheim-under-construction'),
						'back' => esc_html__('Back', 'mannheim-under-construction'),
						'dark_backgrounds' => [plugins_url( 'assets/img/uc_a_blk_muster.svg', __FILE__ ), plugins_url( 'assets/img/uc_c_blk_muster.svg', __FILE__ ), plugins_url( 'assets/img/uc_m_blk_muster.svg', __FILE__ ), plugins_url( 'assets/img/uc_u_blk_muster.svg', __FILE__ )],
						'light_backgrounds' => [plugins_url( 'assets/img/uc_a_yllw_muster.svg', __FILE__ ), plugins_url( 'assets/img/uc_c_yllw_muster.svg', __FILE__ ), plugins_url( 'assets/img/uc_m_yllw_muster.svg', __FILE__ ), plugins_url( 'assets/img/uc_u_yllw_muster.svg', __FILE__ )],
						'tag_data' => $tag_data,
						'map_data' => $map_data,
						'walk_data' => $walk_data,
						'initial_audio' => $initial_audio,
						'initial_walk' => $initial_walk,
						'load_initial_only' => $load_initial_only,
						'text_bonus' => esc_html__('Bonus', 'mannheim-under-construction'),
                        'text_end' => esc_html__('End', 'mannheim-under-construction'),
						'text_walk' => esc_html__('Walk', 'mannheim-under-construction'),
						'text_next_track' => esc_html__('Next track', 'mannheim-under-construction'),
						'text_first_next_track' => esc_html__('Next update', 'mannheim-under-construction'),
						'more_about_working' => esc_html__('More about the topic working', 'mannheim-under-construction'),
						'more_about_living' => esc_html__('More about the topic living', 'mannheim-under-construction'),
						'more_about_climate' => esc_html__('More about the topic climate', 'mannheim-under-construction'),
						'more_about_working_title' => get_post(557)->post_title,
						'more_about_living_title' => get_post(559)->post_title,
						'more_about_climate_title' => get_post(561)->post_title,
						'more_about_working_body' => apply_filters('the_content', get_the_content(null, false, 557)),
						'more_about_living_body' => apply_filters('the_content', get_the_content(null, false, 559)),
						'more_about_climate_body' => apply_filters('the_content', get_the_content(null, false, 561)),
						'initial_campaign' => in_array($_GET['campaign'] ?? '', ['working', 'living', 'climate'], true) ? $_GET['campaign'] : '',
					]
				);
			}
		}, 100 );

		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );

		add_filter( 'page_template', function ( $template ) {
			global $post;
			if ( 'page' === $post->post_type && 'map' === $post->post_name && locate_template( array( 'page-map.php' ) ) !== $template ) {
				return __DIR__ . '/page-map.php';
			}
			return $template;
		});

		add_action('wp_ajax_nopriv_audio_search', [__CLASS__, 'audio_search']);
		add_action('wp_ajax_audio_search', [__CLASS__, 'audio_search']);

		add_filter('post_type_link', function (string $permalink, WP_Post $post){
			if($post->post_type === 'audio-station'){
				$permalink = get_the_permalink(4) . '?audio_id=' . $post->ID;
			}elseif($post->post_type === 'audio-walk'){
				$permalink = get_the_permalink(4) . '?walk_id=' . $post->ID;
			}
			return $permalink;
		}, 10, 2);

		add_action('wp', function ( WP $wp ) {
			if($wp->query_vars['pagename'] === 'klima'){
				wp_redirect(site_url('/?campaign=climate'));
				exit;
			}elseif($wp->query_vars['pagename'] === 'wohnen'){
				wp_redirect(site_url('/?campaign=living'));
				exit;
			}elseif($wp->query_vars['pagename'] === 'arbeit'){
				wp_redirect(site_url('/?campaign=working'));
				exit;
			}
		});
	}

	public static function register_meta_box(WP_Post $post) : void
	{
		add_meta_box('mannheim-under-construction-audio-station', __('Audio station data', 'mannheim-under-construction'), [__CLASS__, 'display_meta_box'], 'audio-station');
		add_meta_box('mannheim-under-construction-audio-walk', __('Audio walk data', 'mannheim-under-construction'), [__CLASS__, 'display_walk_meta_box'], 'audio-walk');
	}

	public static function display_walk_meta_box(): void {
		?>
		<p><b><?php esc_html_e('Link:', 'mannheim-under-construction'); ?></b> <a href="<?php the_permalink(get_the_ID()); ?>" target="_blank" rel="noopener"><?php the_permalink( get_the_ID() ); ?></a></p>
        <p><b><?php esc_html_e('Stations:', 'mannheim-under-construction'); ?></b></p>
        <table id="select-walk-stations">
            <tr><th><?php esc_html_e('Title', 'mannheim-under-construction'); ?></th><th><?php esc_html_e('Audio', 'mannheim-under-construction'); ?></th></tr>
        </table>
        <p><b><?php esc_html_e('Intros:', 'mannheim-under-construction'); ?></b></p>
        <table id="select-walk-intros">
            <tr><th><?php esc_html_e('Title', 'mannheim-under-construction'); ?></th><th><?php esc_html_e('Audio', 'mannheim-under-construction'); ?></th></tr>
        </table>
        <input type="hidden" id="mannheim_under_construction_location_lat" name="mannheim_under_construction_location_lat" value="<?php echo esc_attr(get_post_meta(get_the_ID(), 'mannheim_under_construction_location_lat', true)); ?>">
        <input type="hidden" id="mannheim_under_construction_location_lng" name="mannheim_under_construction_location_lng" value="<?php echo esc_attr(get_post_meta(get_the_ID(), 'mannheim_under_construction_location_lng', true)); ?>">
        <div id="select-map-location"></div>
		<?php
		}

	public static function display_meta_box(): void {
		?>
		<p><b><?php esc_html_e('Link:', 'mannheim-under-construction'); ?></b> <a href="<?php the_permalink(get_the_ID()); ?>" target="_blank" rel="noopener"><?php the_permalink( get_the_ID() ); ?></a></p>
		<b><?php esc_html_e('Encoding guideline:', 'mannheim-under-construction'); ?></b>
		<p><?php esc_html_e('Each audio file should be converted into several formats for best cross browser compatibility and optimizing download size for modern clients. Only the most commonly supported format is required, but for optimization it\'s good to also provide the alternatives. These are:', 'mannheim-under-construction'); ?></p>
		<ul>
			<li><?php esc_html_e('AAC at 96kbps (alternatively MP3 at 128kbps) with 48kHz sample rate, which is supported by almost all browsers.', 'mannheim-under-construction'); ?></li>
			<li><?php esc_html_e('Opus at 64kbps with 48kHz sample rate in an OGG container, which offers significant lower file size with about the same quality. It\'s supported by most modern browsers.', 'mannheim-under-construction'); ?></li>
		</ul>
		<!-- <p><?php esc_html_e('The same applies to video files used in the sign language version. These are:', 'mannheim-under-construction'); ?></p>
		<ul>
			<li><?php esc_html_e('H264 with Pixel format yuv420p, main profile and 25 fps in an mp4 container, which has the moov atom at the beginning, which is supported by almost all browsers .', 'mannheim-under-construction'); ?></li>
			<li><?php esc_html_e('VP9 at CRF 40 and 25 fps in a WebM container, which offers significantly lower file size with about the same quality. It\'s supported by most modern browsers.', 'mannheim-under-construction'); ?></li>
		</ul> -->
        <div>
			<input type="hidden" id="mannheim_under_construction_ogg" name="mannheim_under_construction_ogg" value="<?php echo esc_attr(get_post_meta(get_the_ID(), 'mannheim_under_construction_ogg', true)); ?>">
			<button type="button" class="under-construction-upload" data-field="mannheim_under_construction_ogg" data-type="audio/ogg"><?php esc_html_e('Upload OGG-Audio file', 'mannheim-under-construction'); ?></button>
			<span id="mannheim_under_construction_ogg-selected"><?php echo esc_html(basename(get_post_meta( (get_post_meta(get_the_ID(), 'mannheim_under_construction_ogg', true)), '_wp_attached_file', true ))); ?></span>
		</div>
		<br>
		<div>
			<input type="hidden" id="mannheim_under_construction_aac" name="mannheim_under_construction_aac" value="<?php echo esc_attr(get_post_meta(get_the_ID(), 'mannheim_under_construction_aac', true)); ?>">
			<button type="button" class="under-construction-upload" data-field="mannheim_under_construction_aac" data-type="audio/mpeg,audio/aac"><?php esc_html_e('Upload AAC-Audio file', 'mannheim-under-construction'); ?></button>
			<span id="mannheim_under_construction_aac-selected"><?php echo esc_html(basename(get_post_meta( (get_post_meta(get_the_ID(), 'mannheim_under_construction_aac', true)), '_wp_attached_file', true ))); ?></span>
		</div>
		<br>
		<div>
			<label for="mannheim_under_construction_location"><?php esc_html_e('Location:', 'mannheim-under-construction'); ?>
				<input type="text" id="mannheim_under_construction_location" name="mannheim_under_construction_location" value="<?php echo esc_attr(get_post_meta(get_the_ID(), 'mannheim_under_construction_location', true)); ?>">
			</label>
		</div>
		<br>
		<div>
			<label for="mannheim_under_construction_location_2"><?php esc_html_e('Location 2:', 'mannheim-under-construction'); ?>
				<input type="text" id="mannheim_under_construction_location_2" name="mannheim_under_construction_location_2" value="<?php echo esc_attr(get_post_meta(get_the_ID(), 'mannheim_under_construction_location_2', true)); ?>">
			</label>
		</div>
		<br>
		<div>
			<label for="mannheim_under_construction_credits"><?php esc_html_e('Credits:', 'mannheim-under-construction'); ?>
				<textarea id="mannheim_under_construction_credits" name="mannheim_under_construction_credits" rows="5" cols="30"><?php echo esc_html(get_post_meta(get_the_ID(), 'mannheim_under_construction_credits', true)); ?></textarea>
			</label>
		</div>
		<br>
		<div>
			<?php esc_html_e('Campaign type:', 'mannheim-under-construction');
			$selected = get_post_meta(get_the_ID(), 'mannheim_under_construction_campaign_type', true); ?>
			<label><input type="radio" name="mannheim_under_construction_campaign_type" value=""<?php echo empty($selected) ? ' checked' : ''; ?>><?php echo esc_html_e('None', 'mannheim-under-construction'); ?></label>
			<label><input type="radio" name="mannheim_under_construction_campaign_type" value="working"<?php echo $selected === 'working' ? ' checked' : ''; ?>><?php echo esc_html_e('Working', 'mannheim-under-construction'); ?></label>
			<label><input type="radio" name="mannheim_under_construction_campaign_type" value="living"<?php echo $selected === 'living' ? ' checked' : ''; ?>><?php echo esc_html_e('Living', 'mannheim-under-construction'); ?></label>
			<label><input type="radio" name="mannheim_under_construction_campaign_type" value="climate"<?php echo $selected === 'climate' ? ' checked' : ''; ?>><?php echo esc_html_e('Climate', 'mannheim-under-construction'); ?></label>
		</div>
		<br>
		<div>
			<label for="mannheim_under_construction_location_hidden"><?php esc_html_e('Hide on map:', 'mannheim-under-construction'); ?>
                <?php $hidden = get_post_meta(get_the_ID(), 'mannheim_under_construction_location_hidden', true); ?>
				<select id="mannheim_under_construction_location_hidden" name="mannheim_under_construction_location_hidden"><option value="0" <?php echo $hidden ? '' : 'selected' ?>><?php esc_html_e('No', 'mannheim-under-construction'); ?></option><option value="1" <?php echo $hidden ? 'selected' : '' ?>><?php esc_html_e('Yes', 'mannheim-under-construction'); ?></option></select>
			</label>
		</div>
		<br>
        <p><b><?php esc_html_e('Accordions:', 'mannheim-under-construction'); ?></b></p>
        <table id="select-accordions">
            <tr><th><?php esc_html_e('Title', 'mannheim-under-construction'); ?></th><th><?php esc_html_e('Description', 'mannheim-under-construction'); ?></th></tr>
        </table>
        <p><b><?php esc_html_e('Updates:', 'mannheim-under-construction'); ?></b></p>
        <table id="select-updates">
            <tr><th><?php esc_html_e('Audio Update', 'mannheim-under-construction'); ?></th></tr>
        </table>
        <br>
		<input type="hidden" id="mannheim_under_construction_location_lat" name="mannheim_under_construction_location_lat" value="<?php echo esc_attr(get_post_meta(get_the_ID(), 'mannheim_under_construction_location_lat', true)); ?>">
		<input type="hidden" id="mannheim_under_construction_location_lng" name="mannheim_under_construction_location_lng" value="<?php echo esc_attr(get_post_meta(get_the_ID(), 'mannheim_under_construction_location_lng', true)); ?>">
		<div id="select-map-location"></div>
		<?php
	}

	public static function audio_search(): void {
		$audios = [];
		$message = '';
		$audios_html = '';
		$tax_query = ['relation' => 'AND'];
		if(!empty($_POST['length']) && is_array($_POST['length'])){
			$tax_query []= ['taxonomy' => 'length', 'field' => 'term_id', 'terms' => $_POST['length']];
		}
		if(!empty($_POST['location']) && is_array($_POST['location'])){
			$tax_query []= ['taxonomy' => 'location', 'field' => 'term_id', 'terms' => $_POST['location']];
		}
		if(!empty($_POST['production-date']) && is_array($_POST['production-date'])){
			$tax_query []= ['taxonomy' => 'production-date', 'field' => 'term_id', 'terms' => $_POST['production-date']];
		}
		if(!empty($_POST['s']) || count($tax_query) > 1){
			$query = new WP_Query([
				'post_type' => 'audio-station',
				'posts_per_page' => -1,
				's' => $_POST['s'],
				'tax_query' => $tax_query,
                'meta_query' => [
                    'relation' => 'OR',
                    ['key' => 'mannheim_under_construction_location_hidden', 'value' => '0'],
                    ['key' => 'mannheim_under_construction_location_hidden', 'compare' => 'NOT EXISTS'],
                ],
			]);
			$audios = relevanssi_do_query($query);

			if(!empty($_POST['s'])){
				$query = new WP_Query([
					'post_type' => 'audio-station',
					'posts_per_page' => -1,
					'meta_query' => [
                        'relation' => 'AND',
						['key' => 'mannheim_under_construction_location', 'value' => $_POST['s'], 'compare' => 'LIKE'],
						[
							'relation' => 'OR',
							['key' => 'mannheim_under_construction_location_hidden', 'value' => '0'],
							['key' => 'mannheim_under_construction_location_hidden', 'compare' => 'NOT EXISTS'],
						],
					],
					'tax_query' => $tax_query,
				]);
				$new_audios = array_merge($audios, relevanssi_do_query($query));
				$terms = get_terms([
					'taxonomy' => ['location', 'post_tag'],
					'hide_empty' => true,
					'search' => $_POST['s'],
					'fields' => 'ids',
				]);
				if(is_array($terms) && !empty($terms)) {
					$query = new WP_Query( [
						'post_type' => 'audio-station',
						'posts_per_page' => -1,
						'tax_query' => [
							'relation' => 'OR',
							[ 'taxonomy' => 'location', 'field' => 'term_id', 'terms' => $terms ],
							[ 'taxonomy' => 'post_tag', 'field' => 'term_id', 'terms' => $terms ],
						],
                        'meta_query' => [
	                        'relation' => 'OR',
	                        ['key' => 'mannheim_under_construction_location_hidden', 'value' => '0'],
	                        ['key' => 'mannheim_under_construction_location_hidden', 'compare' => 'NOT EXISTS'],
                        ],
					] ) ;
					$new_audios = array_merge( $audios, relevanssi_do_query($query));
				}
				$audios = [];
				$all_ids = [];
				foreach($new_audios as $audio){
					if(!in_array($audio->ID, $all_ids, true)){
						$audios []= $audio;
						$all_ids []= $audio->ID;
					}
				}
			}
		}
		if(empty($audios)){
			$query = new WP_Query([
				'post_type' => 'audio-station',
				'posts_per_page' => 1,
				'orderby' => 'rand',
                'meta_query' => [
	                'relation' => 'OR',
	                ['key' => 'mannheim_under_construction_location_hidden', 'value' => '0'],
	                ['key' => 'mannheim_under_construction_location_hidden', 'compare' => 'NOT EXISTS'],
                ],
			]);
			$audio = relevanssi_do_query($query)[0];
			$message .= '<p>' . sprintf(esc_html__('The search term "%s" did unfortunately not find any posts :(', 'mannheim-under-construction'), esc_html($_POST['s'])) . '</p>';
			$message .= '<p>' . esc_html__('Enter a new search term... or klick right for the extended search... or klick right for a randomly generated tag that interests you... or click on this randomly selected audio station:', 'mannheim-under-construction') . '</p>';
			$audios_html .= '<li data-id="' . $audio->ID . '">' . esc_html($audio->post_title) . '</li>';
		} else {
			if(!empty($_POST['s'])) {
				$message .= sprintf( __( '%d results for "%s"', 'mannheim-under-construction' ), count($audios), esc_html( $_POST[ 's' ] ) );
			} else {
				$message .= sprintf( __( '%d results', 'mannheim-under-construction' ), count($audios) );
			}
		}
		foreach($audios as $audio){
			$audios_html .= '<li data-id="' . $audio->ID . '">' . esc_html($audio->post_title) . '</li>';
		}
		wp_send_json_success(['audios_html' => $audios_html, 'message' => $message, 'count' => count($audios)]);
		wp_die();
	}

	public static function options_page(): void {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Settings › Mannheim Under Construction', 'mannheim-under-construction' ); ?></h1>
			<form method="post" action="<?php echo admin_url( 'options.php' ); ?>">
				<?php
				settings_fields( 'mannheim-under-construction' );
				do_settings_sections('mannheim-under-construction');
				submit_button(); ?>
			</form>
		</div>
		<?php
	}

}
Mannheim_Under_Constrcution::get_instance();
