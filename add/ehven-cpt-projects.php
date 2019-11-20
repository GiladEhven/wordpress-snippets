<?php

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - //

    if ( ! defined( 'ABSPATH' ) ) exit( 'Nothing to see here. Go <a href="/">home</a>.' );

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - //

    new Ehven_CPT_Projects;

    class Ehven_CPT_Projects {

        public function __construct() {

            $this->create_cpt();

            if ( is_admin() ) {

                add_action( 'load-post.php',     array( $this, 'operate_meta_box' ) );
                add_action( 'load-post-new.php', array( $this, 'operate_meta_box' ) );

            }

        }

        public function create_cpt() {

            add_action( 'init', function() {

                register_post_type( 'ehven_cpt_projects', array(

                    'can_export'                => true,
                    'capability_type'           => 'page',
                    'description'               => __( 'Projects', 'type-zero-child' ),
                    'delete_with_user'          => false,
                    'exclude_from_search'       => false,
                    'has_archive'               => true,
                    'hierarchical'              => false,
                    'label'                     => __( 'Projects', 'type-zero-child' ),
                    'labels'                    => array(

                        'add_new'               => __( 'Add New',                            'type-zero-child' ),
                        'add_new_item'          => __( 'Add New Project',                    'type-zero-child' ),
                        'all_items'             => __( 'All Projects',                       'type-zero-child' ),
                        'archives'              => __( 'Project Archives',                   'type-zero-child' ),
                        'attributes'            => __( 'Project\'s Attributes',              'type-zero-child' ),
                        'edit_item'             => __( 'Edit Project',                       'type-zero-child' ),
                        'featured_image'        => __( 'Project\'s Featured Image',          'type-zero-child' ),
                        'filter_items_list'     => __( 'Filter Projects list',               'type-zero-child' ),
                        'insert_into_item'      => __( 'Insert into Project',                'type-zero-child' ),
                        'items_list'            => __( 'Projects list',                      'type-zero-child' ),
                        'items_list_navigation' => __( 'Project list navigation',            'type-zero-child' ),
                        'menu_name'             => __( 'Projects',                           'type-zero-child' ),
                        'name'                  => _x( 'Projects', 'Post Type General Name', 'type-zero-child' ),
                        'name_admin_bar'        => __( 'Projects',                           'type-zero-child' ),
                        'new_item'              => __( 'New Project',                        'type-zero-child' ),
                        'not_found'             => __( 'Project not found',                  'type-zero-child' ),
                        'not_found_in_trash'    => __( 'Project not found in trash',         'type-zero-child' ),
                        'parent_item_colon'     => __( 'Parent Project: ',                   'type-zero-child' ),
                        'remove_featured_image' => __( 'Remove Project\'s Featured Image',   'type-zero-child' ),
                        'search_items'          => __( 'Search Projects',                    'type-zero-child' ),
                        'set_featured_image'    => __( 'Set Project\'s Featured Image',      'type-zero-child' ),
                        'singular_name'         => _x( 'Project', 'Post Type Singular Name', 'type-zero-child' ),
                        'update_item'           => __( 'Update Project',                     'type-zero-child' ),
                        'uploaded_to_this_item' => __( 'Uploaded to this Project',           'type-zero-child' ),
                        'use_featured_image'    => __( 'Use as Project\'s Featured Image',   'type-zero-child' ),
                        'view_item'             => __( 'View Project',                       'type-zero-child' ),
                        'view_items'            => __( 'View Projects',                      'type-zero-child' ),

                    ),
                    'menu_icon'                 => 'dashicons-universal-access-alt',
                    'menu_position'             => 20,
                    'public'                    => true,
                    'publicly_queryable'        => true,
                    'rest_base'                 => 'projects',
                    'rewrite'                   => array(

                        'slug'                  => 'projects',
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
                'ehven_project_meta_box',
                __( 'About This Project', 'type-zero-child' ),
                array( $this, 'render_meta_box' ),
                'ehven_cpt_projects',
                'side',
                'default'
            );

        }

        public function render_meta_box( $post ) {

            // Authenticate:
            wp_nonce_field( 'ehven_project_nonce_action', 'ehven_project_nonce' );

            // Get existing values:
            $ehven_project_date_end   = get_post_meta( $post->ID, 'ehven_project_date_end',   true );
            $ehven_project_date_start = get_post_meta( $post->ID, 'ehven_project_date_start', true );
            $ehven_project_email      = get_post_meta( $post->ID, 'ehven_project_email',      true );
            $ehven_project_facebook   = get_post_meta( $post->ID, 'ehven_project_facebook',   true );
            $ehven_project_phone      = get_post_meta( $post->ID, 'ehven_project_phone',      true );
            $ehven_project_twitter    = get_post_meta( $post->ID, 'ehven_project_twitter',    true );

            $ehven_project_linkedin   = get_post_meta( $post->ID, 'ehven_project_linkedin',   true );
            $ehven_project_role       = get_post_meta( $post->ID, 'ehven_project_role',       true );

            // Set default values:
            if( empty( $ehven_project_date_end ) )   $ehven_project_date_end   = '';
            if( empty( $ehven_project_date_start ) ) $ehven_project_date_start = '';
            if( empty( $ehven_project_email ) )      $ehven_project_email      = '';
            if( empty( $ehven_project_facebook ) )   $ehven_project_facebook   = '';
            if( empty( $ehven_project_phone ) )      $ehven_project_phone      = '';
            if( empty( $ehven_project_twitter ) )    $ehven_project_twitter    = '';

            if( empty( $ehven_project_linkedin ) )   $ehven_project_linkedin   = '';
            if( empty( $ehven_project_role ) )       $ehven_project_role       = '';

            // Render form:
            echo '<table class="form-table">';

            echo '	<tr>';
            echo '		<th><label for="ehven_project_date_start" class="ehven_project_date_start_label">' . __( 'Start Date', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_project_date_start" name="ehven_project_date_start" class="ehven_project_date_start_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_project_date_start ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_project_date_end" class="ehven_project_date_end_label">' . __( 'End Date', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_project_date_end" name="ehven_project_date_end" class="ehven_project_date_end_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_project_date_end ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_project_phone" class="ehven_project_phone_label">' . __( 'Phone', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_project_phone" name="ehven_project_phone" class="ehven_project_phone_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_project_phone ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_project_email" class="ehven_project_email_label">' . __( 'Email', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_project_email" name="ehven_project_email" class="ehven_project_email_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_project_email ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_project_facebook" class="ehven_project_facebook_label">' . __( 'Facebook', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_project_facebook" name="ehven_project_facebook" class="ehven_project_facebook_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_project_facebook ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_project_twitter" class="ehven_project_twitter_label">' . __( 'Twitter', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_project_twitter" name="ehven_project_twitter" class="ehven_project_twitter_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_project_twitter ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_project_role" class="ehven_project_role_label">' . __( 'Role', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_project_role" name="ehven_project_role" class="ehven_project_role_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_project_role ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '	<tr>';
            echo '		<th><label for="ehven_project_linkedin" class="ehven_project_linkedin_label">' . __( 'LinkedIn', 'type-zero-child' ) . '</label></th>';
            echo '		<td>';
            echo '			<input type="text" id="ehven_project_linkedin" name="ehven_project_linkedin" class="ehven_project_linkedin_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_project_linkedin ) . '">';
            echo '		</td>';
            echo '	</tr>';

            echo '</table>';

        }

        public function save_meta_box_data( $post_id, $post ) {

            // Authenticate:
            $nonce_name   = isset( $_POST['ehven_project_nonce'] ) ? $_POST['ehven_project_nonce'] : '';
            $nonce_action = 'ehven_project_nonce_action';

            if ( ! isset( $nonce_name ) ) return;

            if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) return;

            if ( ! current_user_can( 'edit_post', $post_id ) ) return;

            if ( wp_is_post_autosave( $post_id ) ) return;

            if ( wp_is_post_revision( $post_id ) ) return;

            // Sanitize:
            $ehven_project_new_date_end   = isset( $_POST[ 'ehven_project_date_end' ] )   ? sanitize_text_field( $_POST[ 'ehven_project_date_end' ] )   : '';
            $ehven_project_new_date_start = isset( $_POST[ 'ehven_project_date_start' ] ) ? sanitize_text_field( $_POST[ 'ehven_project_date_start' ] ) : '';
            $ehven_project_new_email      = isset( $_POST[ 'ehven_project_email' ] )      ? sanitize_text_field( $_POST[ 'ehven_project_email' ] )      : '';
            $ehven_project_new_facebook   = isset( $_POST[ 'ehven_project_facebook' ] )   ? sanitize_text_field( $_POST[ 'ehven_project_facebook' ] )   : '';
            $ehven_project_new_phone      = isset( $_POST[ 'ehven_project_phone' ] )      ? sanitize_text_field( $_POST[ 'ehven_project_phone' ] )      : '';
            $ehven_project_new_twitter    = isset( $_POST[ 'ehven_project_twitter' ] )    ? sanitize_text_field( $_POST[ 'ehven_project_twitter' ] )    : '';

            $ehven_project_new_linkedin   = isset( $_POST[ 'ehven_project_linkedin' ] )   ? sanitize_text_field( $_POST[ 'ehven_project_linkedin' ] )   : '';
            $ehven_project_new_role       = isset( $_POST[ 'ehven_project_role' ] )       ? sanitize_text_field( $_POST[ 'ehven_project_role' ] )       : '';

            // Update:
            update_post_meta( $post_id, 'ehven_project_date_end',   $ehven_project_new_date_end );
            update_post_meta( $post_id, 'ehven_project_date_start', $ehven_project_new_date_start );
            update_post_meta( $post_id, 'ehven_project_email',      $ehven_project_new_email );
            update_post_meta( $post_id, 'ehven_project_facebook',   $ehven_project_new_facebook );
            update_post_meta( $post_id, 'ehven_project_phone',      $ehven_project_new_phone );
            update_post_meta( $post_id, 'ehven_project_twitter',    $ehven_project_new_twitter );

            update_post_meta( $post_id, 'ehven_project_linkedin',   $ehven_project_new_linkedin );
            update_post_meta( $post_id, 'ehven_project_role',       $ehven_project_new_role );

        }

    }
