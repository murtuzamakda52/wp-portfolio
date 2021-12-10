<?php 
/**
* Plugin Name: portfolio
* Plugin URI: https://test-projext.000webhostapp.com/
* Description: This is the very first plugin I ever created and this is a unique plugin because using .
* Version: 1.0
* WC tested up to: 5.8.2
* Author: Murtuza Makda(idrish)
* Author URI: https://www.upwork.com/freelancers/~018f06972fe4607ad0
*License: GPL v3
* License URI: https://www.gnu.org/licenses/gpl-3.0.html
**/

function ajax_filter_portfolio_scripts() {
  wp_register_style('portfolio-style', plugins_url('/assets/wp-portfolio.css',__FILE__));
  wp_enqueue_style('portfolio-style');
  wp_register_script('portfolio-script', plugins_url('/assets/ajax-filter-portfolio.js',__FILE__),array('jquery'), '', true);
  wp_enqueue_script('portfolio-script');

  wp_localize_script( 'portfolio-script', 'portfolio_vars', array(
        'portfolio_nonce' => wp_create_nonce( 'portfolio_nonce' ), // Create nonce which we later will use to verify AJAX request
        'portfolio_ajax_url' => admin_url( 'admin-ajax.php' ),
      )
  );
}
add_action('wp_enqueue_scripts', 'ajax_filter_portfolio_scripts', 100);


// Desable gutenberg editor for my custom post type
add_filter('use_block_editor_for_post_type', 'prefix_disable_gutenberg2', 10, 2);
function prefix_disable_gutenberg2($current_status, $post_type)
{
    if ($post_type === 'portfolio') return false;
    return $current_status;
}
add_action('init', 'create_custom_portfolio_type');
 
function create_custom_portfolio_type() {
$supports = array(
'title', // post title
'editor', // post content
'author', // post author
'thumbnail', // featured images
'excerpt', // post excerpt
'custom-fields', // custom fields
'comments', // post comments
'revisions', // post revisions
'post-formats', // post formats
);
 
$labels = array(
'name' => _x('portfolio', 'plural'),
'singular_name' => _x('portfolio', 'singular'),
'menu_name' => _x('portfolio', 'admin menu'),
'name_admin_bar' => _x('portfolio', 'admin bar'),
'add_new' => _x('Add portfolio', 'add new'),
'add_new_item' => __('Add New portfolio'),
'new_item' => __('New portfolio'),
'edit_item' => __('Edit portfolio'),
'view_item' => __('View portfolio'),
'all_items' => __('All portfolio'),
'search_items' => __('Search portfolio'),
'not_found' => __('No portfolio found.'),
);
 
$args = array(
'supports' => $supports,
'labels' => $labels,
'description' => 'Holds our question-answer and specific data',
'public' => true,
'taxonomies' => array( 'category', 'portfolio' ),
'show_ui' => true,
'show_in_menu' => true,
'show_in_nav_menus' => true,
'show_in_admin_bar' => true,
'can_export' => true,
'capability_type' => 'post',
'show_in_rest' => true,
'query_var' => true,
'rewrite' => array('slug' => 'portfolio'),
'has_archive' => true,
'hierarchical' => false,
'menu_position' => 6,
'menu_icon' => 'dashicons-megaphone',
);
 
register_post_type('portfolio', $args); // Register Post type
}

add_filter( 'template_include', 'my_portfolio_templates' );
function my_portfolio_templates( $template ) {
    $post_types = array( 'portfolio' );

    if ( is_post_type_archive( $post_types ) && file_exists( plugin_dir_path(__FILE__) . 'template-portfolio.php' ) ){
        $template = plugin_dir_path(__FILE__) . 'template-portfolio.php';
    }

    if ( is_singular( $post_types ) && file_exists( plugin_dir_path(__FILE__) . 'single-portfolio.php' ) ){
        $template = plugin_dir_path(__FILE__) . 'single-portfolio.php';
    }
    return $template;
}

// Script for getting posts
function ajax_filter_get_portfolio( $taxonomy ) {

  // Verify nonce
  if( !isset( $_POST['portfolio_nonce'] ) || !wp_verify_nonce( $_POST['portfolio_nonce'], 'portfolio_nonce' ) )
    die('Permission denied');

  $taxonomy = $_POST['taxonomy'];

  // WP Query
  $args = array(
    'category_name' => $taxonomy,
    'post_type' => 'portfolio',
    'posts_per_page' => -1,
    'order' =>'ASC',
  );
  // If taxonomy is not set, remove key from array and get all posts
  if( !$taxonomy ) {
    unset( $args['tag'] );
  }

  $query = new WP_Query( $args );

  if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
    <div class="single-portfolio">
    <h2><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(array(400, 300)); ?></a></h2>
    </div>

  <?php endwhile; ?>
  <?php else: ?>
    <h2 class="center-text">No portfolio found</h2>
  <?php endif;

  die();
}

add_action('wp_ajax_filter_portfolio', 'ajax_filter_get_portfolio');
add_action('wp_ajax_nopriv_filter_portfolio', 'ajax_filter_get_portfolio');
?>