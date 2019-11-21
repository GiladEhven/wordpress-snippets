<?php

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - //

    if ( ! defined( 'ABSPATH' ) ) exit( 'Nothing to see here. Go <a href="/">home</a>.' );

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - //

    new Ehven_CPT_Assets;

    class Ehven_CPT_Assets {

        public function __construct() {

            $this->create_cpt();

            if ( is_admin() ) {

                add_action( 'load-post.php',     array( $this, 'operate_meta_box' ) );
                add_action( 'load-post-new.php', array( $this, 'operate_meta_box' ) );

            }

        }

        public function create_cpt() {

            add_action( 'init', function() {

                register_post_type( 'ehven_cpt_assets', array(

                    'can_export'                => true,
                    'capability_type'           => 'page',
                    'description'               => __( 'Assets', 'type-zero-child' ),
                    'delete_with_user'          => false,
                    'exclude_from_search'       => false,
                    'has_archive'               => true,
                    'hierarchical'              => false,
                    'label'                     => __( 'Assets', 'type-zero-child' ),
                    'labels'                    => array(

                        'add_new'               => __( 'Add New',                          'type-zero-child' ),
                        'add_new_item'          => __( 'Add New Asset',                    'type-zero-child' ),
                        'all_items'             => __( 'All Assets',                       'type-zero-child' ),
                        'archives'              => __( 'Asset Archives',                   'type-zero-child' ),
                        'attributes'            => __( 'Asset\'s Attributes',              'type-zero-child' ),
                        'edit_item'             => __( 'Edit Asset',                       'type-zero-child' ),
                        'featured_image'        => __( 'Asset\'s Featured Image',          'type-zero-child' ),
                        'filter_items_list'     => __( 'Filter Assets list',               'type-zero-child' ),
                        'insert_into_item'      => __( 'Insert into Asset',                'type-zero-child' ),
                        'items_list'            => __( 'Assets list',                      'type-zero-child' ),
                        'items_list_navigation' => __( 'Asset list navigation',            'type-zero-child' ),
                        'menu_name'             => __( 'Assets',                           'type-zero-child' ),
                        'name'                  => _x( 'Assets', 'Post Type General Name', 'type-zero-child' ),
                        'name_admin_bar'        => __( 'Assets',                           'type-zero-child' ),
                        'new_item'              => __( 'New Asset',                        'type-zero-child' ),
                        'not_found'             => __( 'Asset not found',                  'type-zero-child' ),
                        'not_found_in_trash'    => __( 'Asset not found in trash',         'type-zero-child' ),
                        'parent_item_colon'     => __( 'Parent Asset: ',                   'type-zero-child' ),
                        'remove_featured_image' => __( 'Remove Asset\'s Featured Image',   'type-zero-child' ),
                        'search_items'          => __( 'Search Assets',                    'type-zero-child' ),
                        'set_featured_image'    => __( 'Set Asset\'s Featured Image',      'type-zero-child' ),
                        'singular_name'         => _x( 'Asset', 'Post Type Singular Name', 'type-zero-child' ),
                        'update_item'           => __( 'Update Asset',                     'type-zero-child' ),
                        'uploaded_to_this_item' => __( 'Uploaded to this Asset',           'type-zero-child' ),
                        'use_featured_image'    => __( 'Use as Asset\'s Featured Image',   'type-zero-child' ),
                        'view_item'             => __( 'View Asset',                       'type-zero-child' ),
                        'view_items'            => __( 'View Assets',                      'type-zero-child' ),

                    ),
                    'menu_icon'                 => 'dashicons-yes-alt',
                    'menu_position'             => 20,
                    'public'                    => true,
                    'publicly_queryable'        => true,
                    'rest_base'                 => 'assets',
                    'rewrite'                   => array(

                        'slug'                  => 'assets',
                        'with_front'            => true,
                        'pages'                 => true,
                        'feeds'                 => true,

                    ),
                    'show_in_admin_bar'         => true,
                    'show_in_menu'              => true,
                    'show_in_nav_menus'         => true,
                    'show_in_rest'              => true,
                    'show_ui'                   => true,
                    'supports'                  => array( 'author', 'comments', 'custom-fields', 'editor', 'excerpt', 'page-attributes', 'revisions', 'thumbnail', 'title' ),
                    'taxonomies'                => array( 'category', 'post_tag' ),

                ) );

            } );

        }

        public function operate_meta_box() {

            add_action( 'add_meta_boxes', array( $this, 'prep_meta_box' ) );
            add_action( 'save_post',      array( $this, 'save_meta_box_data' ), 10, 2 );

        }

        public function prep_meta_box() {

            add_meta_box(
                'ehven_asset_meta_box',
                __( 'About This Asset', 'type-zero-child' ),
                array( $this, 'render_meta_box' ),
                'ehven_cpt_assets',
                'side',
                'default'
            );

        }

        public function render_meta_box( $post ) {

            // Authenticate:
            wp_nonce_field( 'ehven_asset_nonce_action', 'ehven_asset_nonce' );

            // Get existing values:
            $ehven_asset_value      = get_post_meta( $post->ID, 'ehven_asset_value',      true );
            $ehven_asset_value_type = get_post_meta( $post->ID, 'ehven_asset_value_type', true );

            $ehven_asset_linkedin   = get_post_meta( $post->ID, 'ehven_asset_linkedin',   true );
            $ehven_asset_phone      = get_post_meta( $post->ID, 'ehven_asset_phone',      true );
            $ehven_asset_role       = get_post_meta( $post->ID, 'ehven_asset_role',       true );
            $ehven_asset_twitter    = get_post_meta( $post->ID, 'ehven_asset_twitter',    true );

            // Set default values:
            if( empty( $ehven_asset_value ) )      $ehven_asset_value      = '';
            if( empty( $ehven_asset_value_type ) ) $ehven_asset_value_type = '';

            if( empty( $ehven_asset_linkedin ) )   $ehven_asset_linkedin   = '';
            if( empty( $ehven_asset_phone ) )      $ehven_asset_phone      = '';
            if( empty( $ehven_asset_role ) )       $ehven_asset_role       = '';
            if( empty( $ehven_asset_twitter ) )    $ehven_asset_twitter    = '';

            // Render form:
            echo '<table class="form-table">';

            echo '	<tr>';
            echo '		<th><label for="ehven_asset_value" class="ehven_asset_value_label">' . __( 'Value', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_asset_value" name="ehven_asset_value" class="ehven_asset_value_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_asset_value ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_asset_value_type" class="ehven_asset_value_type_label">' . __( 'Value Type', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_asset_value_type" name="ehven_asset_value_type" class="ehven_asset_value_type_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_asset_value_type ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_asset_phone" class="ehven_asset_phone_label">' . __( 'Phone', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_asset_phone" name="ehven_asset_phone" class="ehven_asset_phone_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_asset_phone ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_asset_twitter" class="ehven_asset_twitter_label">' . __( 'Twitter', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_asset_twitter" name="ehven_asset_twitter" class="ehven_asset_twitter_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_asset_twitter ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_asset_role" class="ehven_asset_role_label">' . __( 'Role', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_asset_role" name="ehven_asset_role" class="ehven_asset_role_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_asset_role ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_asset_linkedin" class="ehven_asset_linkedin_label">' . __( 'LinkedIn', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_asset_linkedin" name="ehven_asset_linkedin" class="ehven_asset_linkedin_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_asset_linkedin ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '</table>';

        }

        public function save_meta_box_data( $post_id, $post ) {

            // Authenticate:
            $nonce_name   = isset( $_POST['ehven_asset_nonce'] ) ? $_POST['ehven_asset_nonce'] : '';
            $nonce_action = 'ehven_asset_nonce_action';

            if ( ! isset( $nonce_name ) ) return;

            if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) return;

            if ( ! current_user_can( 'edit_post', $post_id ) ) return;

            if ( wp_is_post_autosave( $post_id ) ) return;

            if ( wp_is_post_revision( $post_id ) ) return;

            // Sanitize:
            $ehven_asset_new_value      = isset( $_POST[ 'ehven_asset_value' ] )      ? sanitize_text_field( $_POST[ 'ehven_asset_value' ] )      : '';
            $ehven_asset_new_value_type = isset( $_POST[ 'ehven_asset_value_type' ] ) ? sanitize_text_field( $_POST[ 'ehven_asset_value_type' ] ) : '';

            $ehven_asset_new_linkedin   = isset( $_POST[ 'ehven_asset_linkedin' ] )   ? sanitize_text_field( $_POST[ 'ehven_asset_linkedin' ] )   : '';
            $ehven_asset_new_phone      = isset( $_POST[ 'ehven_asset_phone' ] )      ? sanitize_text_field( $_POST[ 'ehven_asset_phone' ] )      : '';
            $ehven_asset_new_role       = isset( $_POST[ 'ehven_asset_role' ] )       ? sanitize_text_field( $_POST[ 'ehven_asset_role' ] )       : '';
            $ehven_asset_new_twitter    = isset( $_POST[ 'ehven_asset_twitter' ] )    ? sanitize_text_field( $_POST[ 'ehven_asset_twitter' ] )    : '';

            // Update:
            update_post_meta( $post_id, 'ehven_asset_value',      $ehven_asset_new_value );
            update_post_meta( $post_id, 'ehven_asset_value_type', $ehven_asset_new_value_type );

            update_post_meta( $post_id, 'ehven_asset_linkedin',   $ehven_asset_new_linkedin );
            update_post_meta( $post_id, 'ehven_asset_phone',      $ehven_asset_new_phone );
            update_post_meta( $post_id, 'ehven_asset_role',       $ehven_asset_new_role );
            update_post_meta( $post_id, 'ehven_asset_twitter',    $ehven_asset_new_twitter );

        }

    }
