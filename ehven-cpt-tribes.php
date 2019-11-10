<?php

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - //

    if ( ! defined( 'ABSPATH' ) ) exit( 'Nothing to see here. Go <a href="/">home</a>.' );

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - //

    new Ehven_CPT_Tribes;

    class Ehven_CPT_Tribes {

        public function __construct() {

            $this->create_cpt();

            if ( is_admin() ) {

                add_action( 'load-post.php',     array( $this, 'operate_meta_box' ) );
                add_action( 'load-post-new.php', array( $this, 'operate_meta_box' ) );

            }

        }

        public function create_cpt() {

            add_action( 'init', function() {

                register_post_type( 'ehven_cpt_tribes', array(

                    'can_export'                => true,
                    'capability_type'           => 'page',
                    'description'               => __( 'Tribes', 'type-zero-child' ),
                    'delete_with_user'          => false,
                    'exclude_from_search'       => false,
                    'has_archive'               => true,
                    'hierarchical'              => false,
                    'label'                     => __( 'Tribes', 'type-zero-child' ),
                    'labels'                    => array(

                        'add_new'               => __( 'Add New',                          'type-zero-child' ),
                        'add_new_item'          => __( 'Add New Tribe',                    'type-zero-child' ),
                        'all_items'             => __( 'All Tribes',                       'type-zero-child' ),
                        'archives'              => __( 'Tribes Archives',                  'type-zero-child' ),
                        'attributes'            => __( 'Tribe\'s Attributes',              'type-zero-child' ),
                        'edit_item'             => __( 'Edit Tribe',                       'type-zero-child' ),
                        'featured_image'        => __( 'Tribe\'s Featured Image',          'type-zero-child' ),
                        'filter_items_list'     => __( 'Filter Tribes list',               'type-zero-child' ),
                        'insert_into_item'      => __( 'Insert into Tribe',                'type-zero-child' ),
                        'items_list'            => __( 'Tribes list',                      'type-zero-child' ),
                        'items_list_navigation' => __( 'Tribe list navigation',            'type-zero-child' ),
                        'menu_name'             => __( 'Tribes',                           'type-zero-child' ),
                        'name'                  => _x( 'Tribes', 'Post Type General Name', 'type-zero-child' ),
                        'name_admin_bar'        => __( 'Tribes',                           'type-zero-child' ),
                        'new_item'              => __( 'New Tribe',                        'type-zero-child' ),
                        'not_found'             => __( 'Tribe not found',                  'type-zero-child' ),
                        'not_found_in_trash'    => __( 'Tribe not found in trash',         'type-zero-child' ),
                        'parent_item_colon'     => __( 'Parent Tribe: ',                   'type-zero-child' ),
                        'remove_featured_image' => __( 'Remove Tribe\'s Featured Image',   'type-zero-child' ),
                        'search_items'          => __( 'Search Tribes',                    'type-zero-child' ),
                        'set_featured_image'    => __( 'Set Tribe\'s Featured Image',      'type-zero-child' ),
                        'singular_name'         => _x( 'Tribe', 'Post Type Singular Name', 'type-zero-child' ),
                        'update_item'           => __( 'Update Tribe',                     'type-zero-child' ),
                        'uploaded_to_this_item' => __( 'Uploaded to this Tribe',           'type-zero-child' ),
                        'use_featured_image'    => __( 'Use as Tribe\'s Featured Image',   'type-zero-child' ),
                        'view_item'             => __( 'View Tribe',                       'type-zero-child' ),
                        'view_items'            => __( 'View Tribes',                      'type-zero-child' ),

                    ),
                    'menu_icon'                 => 'dashicons-groups',
                    'menu_position'             => 20,
                    'public'                    => true,
                    'publicly_queryable'        => true,
                    'rest_base'                 => 'tribes',
                    'rewrite'                   => array(

                        'slug'                  => 'tribes',
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
                'ehven_tribe_meta_box',
                __( 'About This Tribe', 'type-zero-child' ),
                array( $this, 'render_meta_box' ),
                'ehven_cpt_tribes',
                'side',
                'default'
            );

        }

        public function render_meta_box( $post ) {

            // Authenticate:
            wp_nonce_field( 'ehven_tribe_nonce_action', 'ehven_tribe_nonce' );

            // Get existing values:
            $ehven_tribe_address_city       = get_post_meta( $post->ID, 'ehven_tribe_address_city',       true );
            $ehven_tribe_address_state      = get_post_meta( $post->ID, 'ehven_tribe_address_state',      true );
            $ehven_tribe_address_street_one = get_post_meta( $post->ID, 'ehven_tribe_address_street_one', true );
            $ehven_tribe_address_street_two = get_post_meta( $post->ID, 'ehven_tribe_address_street_two', true );
            $ehven_tribe_address_zip        = get_post_meta( $post->ID, 'ehven_tribe_address_zip',        true );
            $ehven_tribe_email              = get_post_meta( $post->ID, 'ehven_tribe_email',              true );
            $ehven_tribe_facebook           = get_post_meta( $post->ID, 'ehven_tribe_facebook',           true );
            $ehven_tribe_fax                = get_post_meta( $post->ID, 'ehven_tribe_fax',                true );
            $ehven_tribe_linkedin           = get_post_meta( $post->ID, 'ehven_tribe_linkedin',           true );
            $ehven_tribe_phone              = get_post_meta( $post->ID, 'ehven_tribe_phone',              true );
            $ehven_tribe_twitter            = get_post_meta( $post->ID, 'ehven_tribe_twitter',            true );

            // Set default values:
            if( empty( $ehven_tribe_address_city ) )       $ehven_tribe_address_city       = '';
            if( empty( $ehven_tribe_address_state ) )      $ehven_tribe_address_state      = '';
            if( empty( $ehven_tribe_address_street_one ) ) $ehven_tribe_address_street_one = '';
            if( empty( $ehven_tribe_address_street_two ) ) $ehven_tribe_address_street_two = '';
            if( empty( $ehven_tribe_address_zip ) )        $ehven_tribe_address_zip        = '';
            if( empty( $ehven_tribe_email ) )              $ehven_tribe_email              = '';
            if( empty( $ehven_tribe_facebook ) )           $ehven_tribe_facebook           = '';
            if( empty( $ehven_tribe_fax ) )                $ehven_tribe_fax                = '';
            if( empty( $ehven_tribe_linkedin ) )           $ehven_tribe_linkedin           = '';
            if( empty( $ehven_tribe_phone ) )              $ehven_tribe_phone              = '';
            if( empty( $ehven_tribe_twitter ) )            $ehven_tribe_twitter            = '';

            // Render form:
            echo '<table class="form-table">';

            echo '	<tr>';
            echo '		<th><label for="ehven_tribe_address_street_one" class="ehven_tribe_address_street_one_label">' . __( 'Address', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_tribe_address_street_one" name="ehven_tribe_address_street_one" class="ehven_tribe_address_street_one_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_tribe_address_street_one ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_tribe_address_street_two" class="ehven_tribe_address_street_two_label">' . __( '', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_tribe_address_street_two" name="ehven_tribe_address_street_two" class="ehven_tribe_address_street_two_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_tribe_address_street_two ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_tribe_address_city" class="ehven_tribe_address_city_label">' . __( 'City', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_tribe_address_city" name="ehven_tribe_address_city" class="ehven_tribe_address_city_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_tribe_address_city ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_tribe_address_state" class="ehven_tribe_address_state_label">' . __( 'State', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_tribe_address_state" name="ehven_tribe_address_state" class="ehven_tribe_address_state_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_tribe_address_state ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_tribe_address_zip" class="ehven_tribe_address_zip_label">' . __( 'Zip', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_tribe_address_zip" name="ehven_tribe_address_zip" class="ehven_tribe_address_zip_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_tribe_address_zip ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_tribe_phone" class="ehven_tribe_phone_label">' . __( 'Phone', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_tribe_phone" name="ehven_tribe_phone" class="ehven_tribe_phone_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_tribe_phone ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_tribe_fax" class="ehven_tribe_fax_label">' . __( 'Fax', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_tribe_fax" name="ehven_tribe_fax" class="ehven_tribe_fax_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_tribe_fax ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_tribe_email" class="ehven_tribe_email_label">' . __( 'Email', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_tribe_email" name="ehven_tribe_email" class="ehven_tribe_email_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_tribe_email ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_tribe_facebook" class="ehven_tribe_facebook_label">' . __( 'Facebook', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_tribe_facebook" name="ehven_tribe_facebook" class="ehven_tribe_facebook_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_tribe_facebook ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_tribe_linkedin" class="ehven_tribe_linkedin_label">' . __( 'LinkedIn', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_tribe_linkedin" name="ehven_tribe_linkedin" class="ehven_tribe_linkedin_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_tribe_linkedin ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_tribe_twitter" class="ehven_tribe_twitter_label">' . __( 'Twitter', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_tribe_twitter" name="ehven_tribe_twitter" class="ehven_tribe_twitter_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_tribe_twitter ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '</table>';

        }

        public function save_meta_box_data( $post_id, $post ) {

            // Authenticate:
            $nonce_name   = isset( $_POST['ehven_tribe_nonce'] ) ? $_POST['ehven_tribe_nonce'] : '';
            $nonce_action = 'ehven_tribe_nonce_action';

            if ( ! isset( $nonce_name ) ) return;

            if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) return;

            if ( ! current_user_can( 'edit_post', $post_id ) ) return;

            if ( wp_is_post_autosave( $post_id ) ) return;

            if ( wp_is_post_revision( $post_id ) ) return;

            // Sanitize:
            $ehven_tribe_new_address_city       = isset( $_POST[ 'ehven_tribe_address_city' ] )       ? sanitize_text_field( $_POST[ 'ehven_tribe_address_city' ] )       : '';
            $ehven_tribe_new_address_state      = isset( $_POST[ 'ehven_tribe_address_state' ] )      ? sanitize_text_field( $_POST[ 'ehven_tribe_address_state' ] )      : '';
            $ehven_tribe_new_address_street_one = isset( $_POST[ 'ehven_tribe_address_street_one' ] ) ? sanitize_text_field( $_POST[ 'ehven_tribe_address_street_one' ] ) : '';
            $ehven_tribe_new_address_street_two = isset( $_POST[ 'ehven_tribe_address_street_two' ] ) ? sanitize_text_field( $_POST[ 'ehven_tribe_address_street_two' ] ) : '';
            $ehven_tribe_new_address_zip        = isset( $_POST[ 'ehven_tribe_address_zip' ] )        ? sanitize_text_field( $_POST[ 'ehven_tribe_address_zip' ] )        : '';
            $ehven_tribe_new_email              = isset( $_POST[ 'ehven_tribe_email' ] )              ? sanitize_text_field( $_POST[ 'ehven_tribe_email' ] )              : '';
            $ehven_tribe_new_facebook           = isset( $_POST[ 'ehven_tribe_facebook' ] )           ? sanitize_text_field( $_POST[ 'ehven_tribe_facebook' ] )           : '';
            $ehven_tribe_new_fax                = isset( $_POST[ 'ehven_tribe_fax' ] )                ? sanitize_text_field( $_POST[ 'ehven_tribe_fax' ] )                : '';
            $ehven_tribe_new_linkedin           = isset( $_POST[ 'ehven_tribe_linkedin' ] )           ? sanitize_text_field( $_POST[ 'ehven_tribe_linkedin' ] )           : '';
            $ehven_tribe_new_phone              = isset( $_POST[ 'ehven_tribe_phone' ] )              ? sanitize_text_field( $_POST[ 'ehven_tribe_phone' ] )              : '';
            $ehven_tribe_new_twitter            = isset( $_POST[ 'ehven_tribe_twitter' ] )            ? sanitize_text_field( $_POST[ 'ehven_tribe_twitter' ] )            : '';

            // Update:
            update_post_meta( $post_id, 'ehven_tribe_address_city',       $ehven_tribe_new_address_city );
            update_post_meta( $post_id, 'ehven_tribe_address_state',      $ehven_tribe_new_address_state );
            update_post_meta( $post_id, 'ehven_tribe_address_street_one', $ehven_tribe_new_address_street_one );
            update_post_meta( $post_id, 'ehven_tribe_address_street_two', $ehven_tribe_new_address_street_two );
            update_post_meta( $post_id, 'ehven_tribe_address_zip',        $ehven_tribe_new_address_zip );
            update_post_meta( $post_id, 'ehven_tribe_email',              $ehven_tribe_new_email );
            update_post_meta( $post_id, 'ehven_tribe_facebook',           $ehven_tribe_new_facebook );
            update_post_meta( $post_id, 'ehven_tribe_fax',                $ehven_tribe_new_fax );
            update_post_meta( $post_id, 'ehven_tribe_linkedin',           $ehven_tribe_new_linkedin );
            update_post_meta( $post_id, 'ehven_tribe_phone',              $ehven_tribe_new_phone );
            update_post_meta( $post_id, 'ehven_tribe_twitter',            $ehven_tribe_new_twitter );

        }

    }
