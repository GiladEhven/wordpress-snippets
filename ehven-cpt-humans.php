<?php

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - //

    if ( ! defined( 'ABSPATH' ) ) exit( 'Nothing to see here. Go <a href="/">home</a>.' );

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - //

    new Ehven_CPT_Humans;

    class Ehven_CPT_Humans {

        public function __construct() {

            $this->create_cpt();

            if ( is_admin() ) {

                add_action( 'load-post.php',     array( $this, 'operate_meta_box' ) );
                add_action( 'load-post-new.php', array( $this, 'operate_meta_box' ) );

            }

        }

        public function create_cpt() {

            add_action( 'init', function() {

                register_post_type( 'ehven_cpt_humans', array(

                    'can_export'                => true,
                    'capability_type'           => 'page',
                    'description'               => __( 'Humans', 'type-zero-child' ),
                    'delete_with_user'          => false,
                    'exclude_from_search'       => false,
                    'has_archive'               => true,
                    'hierarchical'              => false,
                    'label'                     => __( 'Humans', 'type-zero-child' ),
                    'labels'                    => array(

                        'add_new'               => __( 'Add New',                          'type-zero-child' ),
                        'add_new_item'          => __( 'Add New Human',                    'type-zero-child' ),
                        'all_items'             => __( 'All Humans',                       'type-zero-child' ),
                        'archives'              => __( 'Human Archives',                   'type-zero-child' ),
                        'attributes'            => __( 'Human\'s Attributes',              'type-zero-child' ),
                        'edit_item'             => __( 'Edit Human',                       'type-zero-child' ),
                        'featured_image'        => __( 'Human\'s Featured Image',          'type-zero-child' ),
                        'filter_items_list'     => __( 'Filter Humans list',               'type-zero-child' ),
                        'insert_into_item'      => __( 'Insert into Human',                'type-zero-child' ),
                        'items_list'            => __( 'Humans list',                      'type-zero-child' ),
                        'items_list_navigation' => __( 'Human list navigation',            'type-zero-child' ),
                        'menu_name'             => __( 'Humans',                           'type-zero-child' ),
                        'name'                  => _x( 'Humans', 'Post Type General Name', 'type-zero-child' ),
                        'name_admin_bar'        => __( 'Humans',                           'type-zero-child' ),
                        'new_item'              => __( 'New Human',                        'type-zero-child' ),
                        'not_found'             => __( 'Human not found',                  'type-zero-child' ),
                        'not_found_in_trash'    => __( 'Human not found in trash',         'type-zero-child' ),
                        'parent_item_colon'     => __( 'Parent Human: ',                   'type-zero-child' ),
                        'remove_featured_image' => __( 'Remove Human\'s Featured Image',   'type-zero-child' ),
                        'search_items'          => __( 'Search Humans',                    'type-zero-child' ),
                        'set_featured_image'    => __( 'Set Human\'s Featured Image',      'type-zero-child' ),
                        'singular_name'         => _x( 'Human', 'Post Type Singular Name', 'type-zero-child' ),
                        'update_item'           => __( 'Update Human',                     'type-zero-child' ),
                        'uploaded_to_this_item' => __( 'Uploaded to this Human',           'type-zero-child' ),
                        'use_featured_image'    => __( 'Use as Human\'s Featured Image',   'type-zero-child' ),
                        'view_item'             => __( 'View Human',                       'type-zero-child' ),
                        'view_items'            => __( 'View Humans',                      'type-zero-child' ),

                    ),
                    'menu_icon'                 => 'dashicons-universal-access-alt',
                    'menu_position'             => 20,
                    'public'                    => true,
                    'publicly_queryable'        => true,
                    'rest_base'                 => 'humans',
                    'rewrite'                   => array(

                        'slug'                  => 'humans',
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
                'ehven_human_meta_box',
                __( 'About This Human', 'type-zero-child' ),
                array( $this, 'render_meta_box' ),
                'ehven_cpt_humans',
                'side',
                'default'
            );

        }

        public function render_meta_box( $post ) {

            // Authenticate:
            wp_nonce_field( 'ehven_human_nonce_action', 'ehven_human_nonce' );

            // Get existing values:
            $ehven_human_email    = get_post_meta( $post->ID, 'ehven_human_email',    true );
            $ehven_human_facebook = get_post_meta( $post->ID, 'ehven_human_facebook', true );
            $ehven_human_linkedin = get_post_meta( $post->ID, 'ehven_human_linkedin', true );
            $ehven_human_phone    = get_post_meta( $post->ID, 'ehven_human_phone',    true );
            $ehven_human_role     = get_post_meta( $post->ID, 'ehven_human_role',     true );
            $ehven_human_twitter  = get_post_meta( $post->ID, 'ehven_human_twitter',  true );

            // Set default values:
            if( empty( $ehven_human_email ) )    $ehven_human_email    = '';
            if( empty( $ehven_human_facebook ) ) $ehven_human_facebook = '';
            if( empty( $ehven_human_linkedin ) ) $ehven_human_linkedin = '';
            if( empty( $ehven_human_phone ) )    $ehven_human_phone    = '';
            if( empty( $ehven_human_role ) )     $ehven_human_role     = '';
            if( empty( $ehven_human_twitter ) )  $ehven_human_twitter  = '';

            // Render form:
            echo '<table class="form-table">';

            echo '	<tr>';
            echo '		<th><label for="ehven_human_role" class="ehven_human_role_label">' . __( 'Role', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_human_role" name="ehven_human_role" class="ehven_human_role_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_human_role ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_human_phone" class="ehven_human_phone_label">' . __( 'Phone', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_human_phone" name="ehven_human_phone" class="ehven_human_phone_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_human_phone ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_human_email" class="ehven_human_email_label">' . __( 'Email', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_human_email" name="ehven_human_email" class="ehven_human_email_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_human_email ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_human_facebook" class="ehven_human_facebook_label">' . __( 'Facebook', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_human_facebook" name="ehven_human_facebook" class="ehven_human_facebook_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_human_facebook ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_human_linkedin" class="ehven_human_linkedin_label">' . __( 'LinkedIn', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_human_linkedin" name="ehven_human_linkedin" class="ehven_human_linkedin_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_human_linkedin ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_human_twitter" class="ehven_human_twitter_label">' . __( 'Twitter', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_human_twitter" name="ehven_human_twitter" class="ehven_human_twitter_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_human_twitter ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '</table>';

        }

        public function save_meta_box_data( $post_id, $post ) {

            // Authenticate:
            $nonce_name   = isset( $_POST['ehven_human_nonce'] ) ? $_POST['ehven_human_nonce'] : '';
            $nonce_action = 'ehven_human_nonce_action';

            if ( ! isset( $nonce_name ) ) return;

            if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) return;

            if ( ! current_user_can( 'edit_post', $post_id ) ) return;

            if ( wp_is_post_autosave( $post_id ) ) return;

            if ( wp_is_post_revision( $post_id ) ) return;

            // Sanitize:
            $ehven_human_new_email    = isset( $_POST[ 'ehven_human_email' ] )    ? sanitize_text_field( $_POST[ 'ehven_human_email' ] )    : '';
            $ehven_human_new_facebook = isset( $_POST[ 'ehven_human_facebook' ] ) ? sanitize_text_field( $_POST[ 'ehven_human_facebook' ] ) : '';
            $ehven_human_new_linkedin = isset( $_POST[ 'ehven_human_linkedin' ] ) ? sanitize_text_field( $_POST[ 'ehven_human_linkedin' ] ) : '';
            $ehven_human_new_phone    = isset( $_POST[ 'ehven_human_phone' ] )    ? sanitize_text_field( $_POST[ 'ehven_human_phone' ] )    : '';
            $ehven_human_new_role     = isset( $_POST[ 'ehven_human_role' ] )     ? sanitize_text_field( $_POST[ 'ehven_human_role' ] )     : '';
            $ehven_human_new_twitter  = isset( $_POST[ 'ehven_human_twitter' ] )  ? sanitize_text_field( $_POST[ 'ehven_human_twitter' ] )  : '';

            // Update:
            update_post_meta( $post_id, 'ehven_human_email',    $ehven_human_new_email );
            update_post_meta( $post_id, 'ehven_human_facebook', $ehven_human_new_facebook );
            update_post_meta( $post_id, 'ehven_human_linkedin', $ehven_human_new_linkedin );
            update_post_meta( $post_id, 'ehven_human_phone',    $ehven_human_new_phone );
            update_post_meta( $post_id, 'ehven_human_role',     $ehven_human_new_role );
            update_post_meta( $post_id, 'ehven_human_twitter',  $ehven_human_new_twitter );

        }

    }
