<?php

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - //

    if ( ! defined( 'ABSPATH' ) ) exit( 'Nothing to see here. Go <a href="/">home</a>.' );

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - //

    new Ehven_CPT_News;

    class Ehven_CPT_News {

        public function __construct() {

            $this->create_cpt();

            if ( is_admin() ) {

                add_action( 'load-post.php',     array( $this, 'operate_meta_box' ) );
                add_action( 'load-post-new.php', array( $this, 'operate_meta_box' ) );

            }

        }

        public function create_cpt() {

            add_action( 'init', function() {

                register_post_type( 'ehven_cpt_news', array(

                    'can_export'                => true,
                    'capability_type'           => 'page',
                    'description'               => __( 'News', 'type-zero-child' ),
                    'delete_with_user'          => false,
                    'exclude_from_search'       => false,
                    'has_archive'               => true,
                    'hierarchical'              => false,
                    'label'                     => __( 'News', 'type-zero-child' ),
                    'labels'                    => array(

                        'add_new'               => __( 'Add New',                               'type-zero-child' ),
                        'add_new_item'          => __( 'Add New News Article',                  'type-zero-child' ),
                        'all_items'             => __( 'All News Articles',                     'type-zero-child' ),
                        'archives'              => __( 'News Archives',                         'type-zero-child' ),
                        'attributes'            => __( 'News Article Attributes',               'type-zero-child' ),
                        'edit_item'             => __( 'Edit News Article',                     'type-zero-child' ),
                        'featured_image'        => __( 'News Article\'s Featured Image',        'type-zero-child' ),
                        'filter_items_list'     => __( 'Filter News list',                      'type-zero-child' ),
                        'insert_into_item'      => __( 'Insert into News Article',              'type-zero-child' ),
                        'items_list'            => __( 'News list',                             'type-zero-child' ),
                        'items_list_navigation' => __( 'News list navigation',                  'type-zero-child' ),
                        'menu_name'             => __( 'News',                                  'type-zero-child' ),
                        'name'                  => _x( 'News', 'Post Type General Name',        'type-zero-child' ),
                        'name_admin_bar'        => __( 'News',                                  'type-zero-child' ),
                        'new_item'              => __( 'New News Article',                      'type-zero-child' ),
                        'not_found'             => __( 'News Articles not found',               'type-zero-child' ),
                        'not_found_in_trash'    => __( 'News Articles not found in trash',      'type-zero-child' ),
                        'parent_item_colon'     => __( 'Parent News Article: ',                 'type-zero-child' ),
                        'remove_featured_image' => __( 'Remove News Article\'s Featured Image', 'type-zero-child' ),
                        'search_items'          => __( 'Search News',                           'type-zero-child' ),
                        'set_featured_image'    => __( 'Set News Article\'s Featured Image',    'type-zero-child' ),
                        'singular_name'         => _x( 'News', 'Post Type Singular Name',       'type-zero-child' ),
                        'update_item'           => __( 'Update News Article',                   'type-zero-child' ),
                        'uploaded_to_this_item' => __( 'Uploaded to this News Article',         'type-zero-child' ),
                        'use_featured_image'    => __( 'Use as News Article\'s Featured Image', 'type-zero-child' ),
                        'view_item'             => __( 'View News',                             'type-zero-child' ),
                        'view_items'            => __( 'View News',                             'type-zero-child' ),

                    ),
                    'menu_icon'                 => 'dashicons-playlist-video',
                    'menu_position'             => 20,
                    'public'                    => true,
                    'publicly_queryable'        => true,
                    'rest_base'                 => 'news',
                    'rewrite'                   => array(

                        'slug'                  => 'news',
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
                'ehven_news_meta_box',
                __( 'About This News Article', 'type-zero-child' ),
                array( $this, 'render_meta_box' ),
                'ehven_cpt_news',
                'side',
                'default'
            );

        }

        public function render_meta_box( $post ) {

            // Authenticate:
            wp_nonce_field( 'ehven_news_nonce_action', 'ehven_news_nonce' );

            // Get existing values:
            $ehven_news_contributors = get_post_meta( $post->ID, 'ehven_news_contributors', true );
            $ehven_news_email        = get_post_meta( $post->ID, 'ehven_news_email',        true );
            $ehven_news_facebook     = get_post_meta( $post->ID, 'ehven_news_facebook',     true );
            $ehven_news_phone        = get_post_meta( $post->ID, 'ehven_news_phone',        true );
            $ehven_news_twitter      = get_post_meta( $post->ID, 'ehven_news_twitter',      true );

            $ehven_news_role         = get_post_meta( $post->ID, 'ehven_news_role',         true );

            // Set default values:
            if( empty( $ehven_news_contributors ) ) $ehven_news_contributors = '';
            if( empty( $ehven_news_email ) )        $ehven_news_email        = '';
            if( empty( $ehven_news_facebook ) )     $ehven_news_facebook     = '';
            if( empty( $ehven_news_phone ) )        $ehven_news_phone        = '';
            if( empty( $ehven_news_twitter ) )      $ehven_news_twitter      = '';

            if( empty( $ehven_news_role ) )         $ehven_news_role         = '';

            // Render form:
            echo '<table class="form-table">';

            echo '	<tr>';
            echo '		<th><label for="ehven_news_contributors" class="ehven_news_contributors_label">' . __( 'Contributors', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_news_contributors" name="ehven_news_contributors" class="ehven_news_contributors_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_news_contributors ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_news_facebook" class="ehven_news_facebook_label">' . __( 'Facebook', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_news_facebook" name="ehven_news_facebook" class="ehven_news_facebook_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_news_facebook ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_news_twitter" class="ehven_news_twitter_label">' . __( 'Twitter', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_news_twitter" name="ehven_news_twitter" class="ehven_news_twitter_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_news_twitter ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_news_email" class="ehven_news_email_label">' . __( 'Email', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_news_email" name="ehven_news_email" class="ehven_news_email_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_news_email ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_news_phone" class="ehven_news_phone_label">' . __( 'Phone', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_news_phone" name="ehven_news_phone" class="ehven_news_phone_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_news_phone ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_news_role" class="ehven_news_role_label">' . __( 'Role', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_news_role" name="ehven_news_role" class="ehven_news_role_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_news_role ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '</table>';

        }

        public function save_meta_box_data( $post_id, $post ) {

            // Authenticate:
            $nonce_name   = isset( $_POST['ehven_news_nonce'] ) ? $_POST['ehven_news_nonce'] : '';
            $nonce_action = 'ehven_news_nonce_action';

            if ( ! isset( $nonce_name ) ) return;

            if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) return;

            if ( ! current_user_can( 'edit_post', $post_id ) ) return;

            if ( wp_is_post_autosave( $post_id ) ) return;

            if ( wp_is_post_revision( $post_id ) ) return;

            // Sanitize:
            $ehven_news_new_email        = isset( $_POST[ 'ehven_news_email' ] )        ? sanitize_text_field( $_POST[ 'ehven_news_email' ] )        : '';
            $ehven_news_new_facebook     = isset( $_POST[ 'ehven_news_facebook' ] )     ? sanitize_text_field( $_POST[ 'ehven_news_facebook' ] )     : '';
            $ehven_news_new_phone        = isset( $_POST[ 'ehven_news_phone' ] )        ? sanitize_text_field( $_POST[ 'ehven_news_phone' ] )        : '';
            $ehven_news_new_twitter      = isset( $_POST[ 'ehven_news_twitter' ] )      ? sanitize_text_field( $_POST[ 'ehven_news_twitter' ] )      : '';

            $ehven_news_new_contributors = isset( $_POST[ 'ehven_news_contributors' ] ) ? sanitize_text_field( $_POST[ 'ehven_news_contributors' ] ) : '';
            $ehven_news_new_role         = isset( $_POST[ 'ehven_news_role' ] )         ? sanitize_text_field( $_POST[ 'ehven_news_role' ] )         : '';

            // Update:
            update_post_meta( $post_id, 'ehven_news_email',        $ehven_news_new_email );
            update_post_meta( $post_id, 'ehven_news_facebook',     $ehven_news_new_facebook );
            update_post_meta( $post_id, 'ehven_news_phone',        $ehven_news_new_phone );
            update_post_meta( $post_id, 'ehven_news_twitter',      $ehven_news_new_twitter );

            update_post_meta( $post_id, 'ehven_news_contributors', $ehven_news_new_contributors );
            update_post_meta( $post_id, 'ehven_news_role',         $ehven_news_new_role );

        }

    }
