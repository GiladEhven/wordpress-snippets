<?php

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - //

    if ( ! defined( 'ABSPATH' ) ) exit( 'Nothing to see here. Go <a href="/">home</a>.' );

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - //

    new Ehven_CPT_Recipes;

    class Ehven_CPT_Recipes {

        public function __construct() {

            $this->create_cpt();

            if ( is_admin() ) {

                add_action( 'load-post.php',     array( $this, 'operate_meta_box' ) );
                add_action( 'load-post-new.php', array( $this, 'operate_meta_box' ) );

            }

        }

        public function create_cpt() {

            add_action( 'init', function() {

                register_post_type( 'ehven_cpt_recipes', array(

                    'can_export'                => true,
                    'capability_type'           => 'page',
                    'description'               => __( 'Recipes', 'type-zero-child' ),
                    'delete_with_user'          => false,
                    'exclude_from_search'       => false,
                    'has_archive'               => true,
                    'hierarchical'              => false,
                    'label'                     => __( 'Recipes', 'type-zero-child' ),
                    'labels'                    => array(

                        'add_new'               => __( 'Add New',                           'type-zero-child' ),
                        'add_new_item'          => __( 'Add New Recipe',                    'type-zero-child' ),
                        'all_items'             => __( 'All Recipes',                       'type-zero-child' ),
                        'archives'              => __( 'Recipe Archives',                   'type-zero-child' ),
                        'attributes'            => __( 'Recipe\'s Attributes',              'type-zero-child' ),
                        'edit_item'             => __( 'Edit Recipe',                       'type-zero-child' ),
                        'featured_image'        => __( 'Recipe\'s Featured Image',          'type-zero-child' ),
                        'filter_items_list'     => __( 'Filter Recipes list',               'type-zero-child' ),
                        'insert_into_item'      => __( 'Insert into Recipe',                'type-zero-child' ),
                        'items_list'            => __( 'Recipes list',                      'type-zero-child' ),
                        'items_list_navigation' => __( 'Recipe list navigation',            'type-zero-child' ),
                        'menu_name'             => __( 'Recipes',                           'type-zero-child' ),
                        'name'                  => _x( 'Recipes', 'Post Type General Name', 'type-zero-child' ),
                        'name_admin_bar'        => __( 'Recipes',                           'type-zero-child' ),
                        'new_item'              => __( 'New Recipe',                        'type-zero-child' ),
                        'not_found'             => __( 'Recipe not found',                  'type-zero-child' ),
                        'not_found_in_trash'    => __( 'Recipe not found in trash',         'type-zero-child' ),
                        'parent_item_colon'     => __( 'Parent Recipe: ',                   'type-zero-child' ),
                        'remove_featured_image' => __( 'Remove Recipe\'s Featured Image',   'type-zero-child' ),
                        'search_items'          => __( 'Search Recipes',                    'type-zero-child' ),
                        'set_featured_image'    => __( 'Set Recipe\'s Featured Image',      'type-zero-child' ),
                        'singular_name'         => _x( 'Recipe', 'Post Type Singular Name', 'type-zero-child' ),
                        'update_item'           => __( 'Update Recipe',                     'type-zero-child' ),
                        'uploaded_to_this_item' => __( 'Uploaded to this Recipe',           'type-zero-child' ),
                        'use_featured_image'    => __( 'Use as Recipe\'s Featured Image',   'type-zero-child' ),
                        'view_item'             => __( 'View Recipe',                       'type-zero-child' ),
                        'view_items'            => __( 'View Recipes',                      'type-zero-child' ),

                    ),
                    'menu_icon'                 => 'dashicons-carrot',
                    'menu_position'             => 20,
                    'public'                    => true,
                    'publicly_queryable'        => true,
                    'rest_base'                 => 'recipes',
                    'rewrite'                   => array(

                        'slug'                  => 'recipes',
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
                'ehven_recipe_meta_box',
                __( 'About This Recipe', 'type-zero-child' ),
                array( $this, 'render_meta_box' ),
                'ehven_cpt_recipes',
                'side',
                'default'
            );

        }

        public function render_meta_box( $post ) {

            // Authenticate:
            wp_nonce_field( 'ehven_recipe_nonce_action', 'ehven_recipe_nonce' );

            // Get existing values:
            $ehven_recipe_calories    = get_post_meta( $post->ID, 'ehven_recipe_calories',    true );
            $ehven_recipe_email       = get_post_meta( $post->ID, 'ehven_recipe_email',       true );
            $ehven_recipe_facebook    = get_post_meta( $post->ID, 'ehven_recipe_facebook',    true );
            $ehven_recipe_ingredients = get_post_meta( $post->ID, 'ehven_recipe_ingredients', true );
            $ehven_recipe_linkedin    = get_post_meta( $post->ID, 'ehven_recipe_linkedin',    true );
            $ehven_recipe_serving     = get_post_meta( $post->ID, 'ehven_recipe_serving',     true );

            // Set default values:
            if( empty( $ehven_recipe_calories ) )    $ehven_recipe_calories    = '';
            if( empty( $ehven_recipe_email ) )       $ehven_recipe_email       = '';
            if( empty( $ehven_recipe_facebook ) )    $ehven_recipe_facebook    = '';
            if( empty( $ehven_recipe_ingredients ) ) $ehven_recipe_ingredients = '';
            if( empty( $ehven_recipe_linkedin ) )    $ehven_recipe_linkedin    = '';
            if( empty( $ehven_recipe_serving ) )     $ehven_recipe_serving     = '';

            // Render form:
            echo '<table class="form-table">';

            echo '  <tr>';
            echo '      <th><label for="ehven_recipe_serving" class="ehven_recipe_serving_label">' . __( 'Serving Size', 'type-zero-child' ) . '</label></th>';
            echo '      <td>';
            echo '          <input type="text" id="ehven_recipe_serving" name="ehven_recipe_serving" class="ehven_recipe_serving_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_recipe_serving ) . '">';
            echo '      </td>';
            echo '  </tr>';

            echo '  <tr>';
            echo '      <th><label for="ehven_recipe_calories" class="ehven_recipe_calories_label">' . __( 'Calories per Serving', 'type-zero-child' ) . '</label></th>';
            echo '      <td>';
            echo '          <input type="text" id="ehven_recipe_calories" name="ehven_recipe_calories" class="ehven_recipe_calories_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_recipe_calories ) . '">';
            echo '      </td>';
            echo '  </tr>';

            echo '  <tr>';
            echo '      <th><label for="ehven_recipe_ingredients" class="ehven_recipe_ingredients_label">' . __( 'Ingredients', 'type-zero-child' ) . '</label></th>';
            echo '      <td>';
            echo '          <input type="text" id="ehven_recipe_ingredients" name="ehven_recipe_ingredients" class="ehven_recipe_ingredients_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_recipe_ingredients ) . '">';
            echo '      </td>';
            echo '  </tr>';

            echo '  <tr>';
            echo '      <th><label for="ehven_recipe_email" class="ehven_recipe_email_label">' . __( 'Email', 'type-zero-child' ) . '</label></th>';
            echo '      <td>';
            echo '          <input type="text" id="ehven_recipe_email" name="ehven_recipe_email" class="ehven_recipe_email_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_recipe_email ) . '">';
            echo '      </td>';
            echo '  </tr>';

            echo '  <tr>';
            echo '      <th><label for="ehven_recipe_facebook" class="ehven_recipe_facebook_label">' . __( 'Facebook', 'type-zero-child' ) . '</label></th>';
            echo '      <td>';
            echo '          <input type="text" id="ehven_recipe_facebook" name="ehven_recipe_facebook" class="ehven_recipe_facebook_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_recipe_facebook ) . '">';
            echo '      </td>';
            echo '  </tr>';

            echo '  <tr>';
            echo '      <th><label for="ehven_recipe_linkedin" class="ehven_recipe_linkedin_label">' . __( 'LinkedIn', 'type-zero-child' ) . '</label></th>';
            echo '      <td>';
            echo '          <input type="text" id="ehven_recipe_linkedin" name="ehven_recipe_linkedin" class="ehven_recipe_linkedin_field" placeholder="' . esc_attr__( '', 'type-zero-child' ) . '" value="' . esc_attr( $ehven_recipe_linkedin ) . '">';
            echo '      </td>';
            echo '  </tr>';

            echo '</table>';

        }

        public function save_meta_box_data( $post_id, $post ) {

            // Authenticate:
            $nonce_name   = isset( $_POST['ehven_recipe_nonce'] ) ? $_POST['ehven_recipe_nonce'] : '';
            $nonce_action = 'ehven_recipe_nonce_action';

            if ( ! isset( $nonce_name ) ) return;

            if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) return;

            if ( ! current_user_can( 'edit_post', $post_id ) ) return;

            if ( wp_is_post_autosave( $post_id ) ) return;

            if ( wp_is_post_revision( $post_id ) ) return;

            // Sanitize:
            $ehven_recipe_new_calories    = isset( $_POST[ 'ehven_recipe_calories' ] )    ? sanitize_text_field( $_POST[ 'ehven_recipe_calories' ] )    : '';
            $ehven_recipe_new_email       = isset( $_POST[ 'ehven_recipe_email' ] )       ? sanitize_text_field( $_POST[ 'ehven_recipe_email' ] )       : '';
            $ehven_recipe_new_facebook    = isset( $_POST[ 'ehven_recipe_facebook' ] )    ? sanitize_text_field( $_POST[ 'ehven_recipe_facebook' ] )    : '';
            $ehven_recipe_new_ingredients = isset( $_POST[ 'ehven_recipe_ingredients' ] ) ? sanitize_text_field( $_POST[ 'ehven_recipe_ingredients' ] ) : '';
            $ehven_recipe_new_linkedin    = isset( $_POST[ 'ehven_recipe_linkedin' ] )    ? sanitize_text_field( $_POST[ 'ehven_recipe_linkedin' ] )    : '';
            $ehven_recipe_new_serving     = isset( $_POST[ 'ehven_recipe_serving' ] )     ? sanitize_text_field( $_POST[ 'ehven_recipe_serving' ] )     : '';

            // Update:
            update_post_meta( $post_id, 'ehven_recipe_calories',    $ehven_recipe_new_calories );
            update_post_meta( $post_id, 'ehven_recipe_email',       $ehven_recipe_new_email );
            update_post_meta( $post_id, 'ehven_recipe_facebook',    $ehven_recipe_new_facebook );
            update_post_meta( $post_id, 'ehven_recipe_ingredients', $ehven_recipe_new_ingredients );
            update_post_meta( $post_id, 'ehven_recipe_linkedin',    $ehven_recipe_new_linkedin );
            update_post_meta( $post_id, 'ehven_recipe_serving',     $ehven_recipe_new_serving );

        }

    }
