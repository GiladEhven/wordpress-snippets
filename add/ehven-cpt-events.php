<?php

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - //

    if ( ! defined( 'ABSPATH' ) ) exit( 'Nothing to see here. Go <a href="/">home</a>.' );

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - //

    new Ehven_CPT_Events;

    class Ehven_CPT_Events {

        public function __construct() {

            $this->create_cpt();

            if ( is_admin() ) {

                add_action( 'load-post.php',     array( $this, 'operate_meta_box' ) );
                add_action( 'load-post-new.php', array( $this, 'operate_meta_box' ) );

            }

        }

        public function create_cpt() {

            add_action( 'init', function() {

                register_post_type( 'ehven_cpt_events', array(

                    'can_export'                => true,
                    'capability_type'           => 'page',
                    'description'               => __( 'Events', 'type-zero-child' ),
                    'delete_with_user'          => false,
                    'exclude_from_search'       => false,
                    'has_archive'               => true,
                    'hierarchical'              => false,
                    'label'                     => __( 'Events', 'type-zero-child' ),
                    'labels'                    => array(

                        'add_new'               => __( 'Add New',                          'type-zero-child' ),
                        'add_new_item'          => __( 'Add New Event',                    'type-zero-child' ),
                        'all_items'             => __( 'All Events',                       'type-zero-child' ),
                        'archives'              => __( 'Event Archives',                   'type-zero-child' ),
                        'attributes'            => __( 'Event\'s Attributes',              'type-zero-child' ),
                        'edit_item'             => __( 'Edit Event',                       'type-zero-child' ),
                        'featured_image'        => __( 'Event\'s Featured Image',          'type-zero-child' ),
                        'filter_items_list'     => __( 'Filter Events list',               'type-zero-child' ),
                        'insert_into_item'      => __( 'Insert into Event',                'type-zero-child' ),
                        'items_list'            => __( 'Events list',                      'type-zero-child' ),
                        'items_list_navigation' => __( 'Event list navigation',            'type-zero-child' ),
                        'menu_name'             => __( 'Events',                           'type-zero-child' ),
                        'name'                  => _x( 'Events', 'Post Type General Name', 'type-zero-child' ),
                        'name_admin_bar'        => __( 'Events',                           'type-zero-child' ),
                        'new_item'              => __( 'New Event',                        'type-zero-child' ),
                        'not_found'             => __( 'Event not found',                  'type-zero-child' ),
                        'not_found_in_trash'    => __( 'Event not found in trash',         'type-zero-child' ),
                        'parent_item_colon'     => __( 'Parent Event: ',                   'type-zero-child' ),
                        'remove_featured_image' => __( 'Remove Event\'s Featured Image',   'type-zero-child' ),
                        'search_items'          => __( 'Search Events',                    'type-zero-child' ),
                        'set_featured_image'    => __( 'Set Event\'s Featured Image',      'type-zero-child' ),
                        'singular_name'         => _x( 'Event', 'Post Type Singular Name', 'type-zero-child' ),
                        'update_item'           => __( 'Update Event',                     'type-zero-child' ),
                        'uploaded_to_this_item' => __( 'Uploaded to this Event',           'type-zero-child' ),
                        'use_featured_image'    => __( 'Use as Event\'s Featured Image',   'type-zero-child' ),
                        'view_item'             => __( 'View Event',                       'type-zero-child' ),
                        'view_items'            => __( 'View Events',                      'type-zero-child' ),

                    ),
                    'menu_icon'                 => 'dashicons-calendar-alt',
                    'menu_position'             => 20,
                    'public'                    => true,
                    'publicly_queryable'        => true,
                    'rest_base'                 => 'events',
                    'rewrite'                   => array(

                        'slug'                  => 'events',
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
                'ehven_event_meta_box',
                __( 'About This Event', 'type-zero-child' ),
                array( $this, 'render_meta_box' ),
                'ehven_cpt_events',
                'side',
                'default'
            );

        }

        public function render_meta_box( $post ) {

            // Authenticate:
            wp_nonce_field( 'ehven_event_nonce_action', 'ehven_event_nonce' );

            // Get existing values:
            $ehven_event_date_end   = get_post_meta( $post->ID, 'ehven_event_date_end',   true );
            $ehven_event_date_start = get_post_meta( $post->ID, 'ehven_event_date_start', true );
            $ehven_event_email      = get_post_meta( $post->ID, 'ehven_event_email',      true );
            $ehven_event_facebook   = get_post_meta( $post->ID, 'ehven_event_facebook',   true );
            $ehven_event_phone      = get_post_meta( $post->ID, 'ehven_event_phone',      true );
            $ehven_event_time_end   = get_post_meta( $post->ID, 'ehven_event_time_end',   true );
            $ehven_event_time_start = get_post_meta( $post->ID, 'ehven_event_time_start', true );
            $ehven_event_twitter    = get_post_meta( $post->ID, 'ehven_event_twitter',    true );

            $ehven_event_role       = get_post_meta( $post->ID, 'ehven_event_role',       true );

            // Set default values:
            if( empty( $ehven_event_date_end ) )   $ehven_event_date_end   = '';
            if( empty( $ehven_event_date_start ) ) $ehven_event_date_start = '';
            if( empty( $ehven_event_email ) )      $ehven_event_email      = '';
            if( empty( $ehven_event_facebook ) )   $ehven_event_facebook   = '';
            if( empty( $ehven_event_phone ) )      $ehven_event_phone      = '';
            if( empty( $ehven_event_time_end ) )   $ehven_event_time_end   = '';
            if( empty( $ehven_event_time_start ) ) $ehven_event_time_start = '';
            if( empty( $ehven_event_twitter ) )    $ehven_event_twitter    = '';

            if( empty( $ehven_event_role ) )       $ehven_event_role       = '';

            // Render form:
            echo '<table class="form-table">';

            echo '	<tr>';
            echo '		<th><label for="ehven_event_date_start" class="ehven_event_date_start_label">' . __( 'Start Date', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_event_date_start" name="ehven_event_date_start" class="ehven_event_date_start_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_event_date_start ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_event_date_end" class="ehven_event_date_end_label">' . __( 'End Date', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_event_date_end" name="ehven_event_date_end" class="ehven_event_date_end_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_event_date_end ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_event_time_start" class="ehven_event_time_start_label">' . __( 'Start Time', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_event_time_start" name="ehven_event_time_start" class="ehven_event_time_start_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_event_time_start ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_event_time_end" class="ehven_event_time_end_label">' . __( 'End Time', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_event_time_end" name="ehven_event_time_end" class="ehven_event_time_end_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_event_time_end ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_event_phone" class="ehven_event_phone_label">' . __( 'Phone', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_event_phone" name="ehven_event_phone" class="ehven_event_phone_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_event_phone ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_event_email" class="ehven_event_email_label">' . __( 'Email', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_event_email" name="ehven_event_email" class="ehven_event_email_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_event_email ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_event_facebook" class="ehven_event_facebook_label">' . __( 'Facebook', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_event_facebook" name="ehven_event_facebook" class="ehven_event_facebook_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_event_facebook ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_event_twitter" class="ehven_event_twitter_label">' . __( 'Twitter', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_event_twitter" name="ehven_event_twitter" class="ehven_event_twitter_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_event_twitter ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_event_role" class="ehven_event_role_label">' . __( 'Role', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_event_role" name="ehven_event_role" class="ehven_event_role_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_event_role ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '</table>';

        }

        public function save_meta_box_data( $post_id, $post ) {

            // Authenticate:
            $nonce_name   = isset( $_POST['ehven_event_nonce'] ) ? $_POST['ehven_event_nonce'] : '';
            $nonce_action = 'ehven_event_nonce_action';

            if ( ! isset( $nonce_name ) ) return;

            if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) return;

            if ( ! current_user_can( 'edit_post', $post_id ) ) return;

            if ( wp_is_post_autosave( $post_id ) ) return;

            if ( wp_is_post_revision( $post_id ) ) return;

            // Sanitize:
            $ehven_event_new_date_end   = isset( $_POST[ 'ehven_event_date_end' ] )   ? sanitize_text_field( $_POST[ 'ehven_event_date_end' ] )   : '';
            $ehven_event_new_date_start = isset( $_POST[ 'ehven_event_date_start' ] ) ? sanitize_text_field( $_POST[ 'ehven_event_date_start' ] ) : '';
            $ehven_event_new_email      = isset( $_POST[ 'ehven_event_email' ] )      ? sanitize_text_field( $_POST[ 'ehven_event_email' ] )      : '';
            $ehven_event_new_facebook   = isset( $_POST[ 'ehven_event_facebook' ] )   ? sanitize_text_field( $_POST[ 'ehven_event_facebook' ] )   : '';
            $ehven_event_new_phone      = isset( $_POST[ 'ehven_event_phone' ] )      ? sanitize_text_field( $_POST[ 'ehven_event_phone' ] )      : '';
            $ehven_event_new_time_end   = isset( $_POST[ 'ehven_event_time_end' ] )   ? sanitize_text_field( $_POST[ 'ehven_event_time_end' ] )   : '';
            $ehven_event_new_time_start = isset( $_POST[ 'ehven_event_time_start' ] ) ? sanitize_text_field( $_POST[ 'ehven_event_time_start' ] ) : '';
            $ehven_event_new_twitter    = isset( $_POST[ 'ehven_event_twitter' ] )    ? sanitize_text_field( $_POST[ 'ehven_event_twitter' ] )    : '';

            $ehven_event_new_role       = isset( $_POST[ 'ehven_event_role' ] )       ? sanitize_text_field( $_POST[ 'ehven_event_role' ] )       : '';

            // Update:
            update_post_meta( $post_id, 'ehven_event_date_end',   $ehven_event_new_date_end );
            update_post_meta( $post_id, 'ehven_event_date_start', $ehven_event_new_date_start );
            update_post_meta( $post_id, 'ehven_event_email',      $ehven_event_new_email );
            update_post_meta( $post_id, 'ehven_event_facebook',   $ehven_event_new_facebook );
            update_post_meta( $post_id, 'ehven_event_phone',      $ehven_event_new_phone );
            update_post_meta( $post_id, 'ehven_event_time_end',   $ehven_event_new_time_end );
            update_post_meta( $post_id, 'ehven_event_time_start', $ehven_event_new_time_start );
            update_post_meta( $post_id, 'ehven_event_twitter',    $ehven_event_new_twitter );

            update_post_meta( $post_id, 'ehven_event_role',       $ehven_event_new_role );

        }

    }
