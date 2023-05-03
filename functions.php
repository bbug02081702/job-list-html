<?php
/**
 * Deva functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Deva
 */





//------------------------------

defined( 'DEVA_T_URI' ) or define( 'DEVA_T_URI', get_template_directory_uri() );
defined( 'DEVA_T_PATH' ) or define( 'DEVA_T_PATH', get_template_directory() );

require_once ABSPATH . 'wp-admin/includes/plugin.php';

require_once DEVA_T_PATH . '/include/class-tgm-plugin-activation.php';
require_once DEVA_T_PATH . '/include/custom-header.php';
require_once DEVA_T_PATH . '/include/actions-config.php';
require_once DEVA_T_PATH . '/include/helper-function.php';
require_once DEVA_T_PATH . '/include/customizer.php';


require DEVA_T_PATH . '/vendor/autoload.php';


/**
 * Initialize the plugin tracker
 *
 * @return void
 */
function appsero_init_tracker_deva() {

	if ( ! class_exists( 'Appsero\Client' ) ) {
		require_once __DIR__ . '/vendor/appsero/client/src/Client.php';
	}

	$client = new \Appsero\Client( '26caecda-b4e6-4242-b1e7-c7f1ca06f938', 'Deva', __FILE__ );

	// Active insights
	$client->insights()->init();

	// Active automatic updater
	$client->updater();

}

appsero_init_tracker_deva();

add_action('init', 'deva_demo_layouts');

if ( ! function_exists( 'deva_demo_layouts' ) ){
	function deva_demo_layouts(){
		$shortcodes_dir = get_template_directory() . '/aheto';
		$files          = glob( $shortcodes_dir . '/*/controllers/*.php' );

		if(is_array($files) && count($files) > 0){
			foreach ( $files as $file ) {

				if(file_exists($file)){
					require_once( $file );
				}
			}
		}
	}
}


if ( ! function_exists( 'deva_setup' ) ) :

	function deva_setup() {

		register_nav_menus( array( 'primary-menu' => esc_html__( 'Primary menu', 'deva' ) ) );
		load_theme_textdomain( 'deva', get_template_directory() . '/languages' );


		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
		add_theme_support( 'post-formats', array(
			'aside',
			'gallery',
			'link',
			'image',
			'quote',
			'status',
			'video',
			'audio',
			'chat'
		) );

		add_theme_support( 'woocommerce' );


		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'deva_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );


		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;

add_action( 'after_setup_theme', 'deva_setup' );

// Disable REST API link tag
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );




/**
 * Define the metabox and field configurations.
 */
if ( function_exists( 'is_woocommerce' ) ) {
	function deva_product_info() {
		$cmb2 = new_cmb2_box( array(
			'id'           => 'deva_product_short_info',
			'title'        => __( 'Product Short Info', 'deva' ),
			'object_types' => array( 'product' ),
			'context'      => 'normal',
			'priority'     => 'low',
			'show_names'   => true,
		) );
		$group_field_id2 = $cmb2->add_field( array(
			'id'          => 'deva_product_short_items',
			'type'        => 'group',
			'description' => __( 'You can add more information about your product in short info', 'deva' ),
			'options'     => array(
				'group_title'   => __( 'Add info for product info', 'deva' ),
				'add_button'    => __( 'Add another info', 'deva' ),
				'remove_button' => __( 'Remove info', 'deva' ),
				'sortable'      => true,
			),
		) );
		$cmb2->add_group_field( $group_field_id2, array(
			'name' => 'Info title for this item',
			'id'   => 'deva_short_title',
			'type' => 'text',
		) );
		$cmb2->add_group_field( $group_field_id2, array(
			'name' => 'Info text for this item',
			'id'   => 'deva_short_text',
			'type' => 'wysiwyg',
		) );
		$cmb2->add_group_field( $group_field_id2, array(
			'name'    => 'Info icon for this item',
			'id'      => 'deva_short_image',
			'type'    => 'file',
			'options' => array(
				'url' => false,
			),
		) );
		$cmb = new_cmb2_box( array(
			'id'           => 'deva_product_info',
			'title'        => __( 'Product Review', 'deva' ),
			'object_types' => array( 'product' ),
			'context'      => 'normal',
			'priority'     => 'low',
			'show_names'   => true,
		) );
		$group_field_id = $cmb->add_field( array(
			'id'          => 'deva_product_items',
			'type'        => 'group',
			'description' => __( 'You can add more information about your product in Product Review', 'deva' ),
			'options'     => array(
				'group_title'   => __( 'Add info for product review', 'deva' ),
				'add_button'    => __( 'Add another info', 'deva' ),
				'remove_button' => __( 'Remove info', 'deva' ),
				'sortable'      => true,
			),
		) );
		$cmb->add_group_field( $group_field_id, array(
			'name' => 'Product review text for this item',
			'id'   => 'deva_text',
			'type' => 'wysiwyg',
		) );
		$cmb->add_group_field( $group_field_id, array(
			'name'    => 'Product review image for this item',
			'id'      => 'deva_image',
			'type'    => 'file',
			'options' => array(
				'url' => false,
			),
		) );
		$cmb->add_group_field( $group_field_id, array(
			'name' => 'Reverse content?',
			'id'   => 'deva_checkbox',
			'type' => 'checkbox',
		) );
	}
	add_action( 'cmb2_admin_init', 'deva_product_info' );
	class deva_clear_all_widget extends WP_Widget {
		function __construct() {
			parent::__construct(
				'deva_clear_all_widget',
				esc_html__( 'Woocommerce Clear All Button', 'deva' ),
				array( 'description' => __( 'Button for reset filters.', 'deva' ), )
			);
		}
		public function widget( $args, $instance ) {
			$title = apply_filters( 'widget_title', $instance['title'] );
			echo $args['before_widget'];
			if ( ! empty( $title ) ) { ?>
                <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"
                   class="deva-product-filter-reset"><?php echo esc_html( $title ); ?></a>
			<?php }
			echo $args['after_widget'];
		}
		public function form( $instance ) {
			if ( isset( $instance['title'] ) ) {
				$title = $instance['title'];
			} else {
				$title = esc_html__( 'Clear All', 'deva' );
			} ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Button Text:', 'deva' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
                       name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
                       value="<?php echo esc_attr( $title ); ?>"/>
            </p>
			<?php
		}
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			return $instance;
		}
	}
}
if ( ! class_exists( 'deva_top_posts' ) ) {
	class deva_top_posts extends WP_Widget {
		function __construct() {
			parent::__construct(
				'deva_top_posts',
				esc_html__( 'Deva Popular Posts', 'deva' ),
				array( 'description' => __( 'Popular posts.', 'deva' ), )
			);
		}
		public function widget( $args, $instance ) {
			$title = apply_filters( 'widget_title', $instance['title'] );
			echo $args['before_widget'];
			if ( ! empty( $title ) ) { ?>
                <h4 class="widget-title"><?php echo esc_html( $title ); ?></h4>
			<?php }
			$popular = new WP_Query( array(
				'posts_per_page' => 4,
				'meta_key'       => 'post_views_count',
				'orderby'        => 'meta_value_num',
				'order'          => 'DESC'
			) );
			$counter = 1;
			if ( $popular->have_posts() ) : while ( $popular->have_posts() ) : $popular->the_post();
				$image_id = get_post_thumbnail_id( get_the_ID() );
				$top_class = !empty($image_id) ? 'image' : '';?>
                <div class="deva-widget-popular--item">
                    <div class="deva-widget-popular--image <?php echo esc_attr( $top_class ); ?>">
                        <span><?php echo esc_html( $counter ); ?></span>
						<?php if ( $image_id ) {
							$image     = wp_get_attachment_image_url( $image_id, 'thumbnail' );
							$image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true ); ?>
                            <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>">
						<?php } ?>
                    </div>
                    <div class="deva-widget-popular--content">
                        <a href="<?php the_permalink(); ?>"><h5><?php the_title(); ?></h5></a>
                        <div class="deva-widget-popular--author">
                            <span><b><?php echo esc_html( get_the_author() ); ?></b></span>
                            <span><?php echo sprintf( esc_html__( '%s ago', 'deva' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
                        </div>
                    </div>
                </div>
				<?php
				$counter ++;
			endwhile; endif;
			wp_reset_query();
			echo $args['after_widget'];
		}
		public function form( $instance ) {
			if ( isset( $instance['title'] ) ) {
				$title = $instance['title'];
			} else {
				$title = esc_html__( 'Top Picks', 'deva' );
			} ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'deva' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
                       name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
                       value="<?php echo esc_attr( $title ); ?>"/>
            </p>
			<?php
		}
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			return $instance;
		}
	}
}



if ( ! class_exists( 'deva_reading_posts' ) ) {
	class deva_reading_posts extends WP_Widget {
		function __construct() {
			parent::__construct(
				'deva_reading_posts',
				esc_html__( 'Deva Short Reading Posts', 'deva' ),
				array( 'description' => __( 'Short Reading posts.', 'deva' ), )
			);
		}
		public function widget( $args, $instance ) {
			$title = apply_filters( 'widget_title', $instance['title'] );
			echo $args['before_widget'];
			if ( ! empty( $title ) ) { ?>
                <h4 class="widget-title"><?php echo esc_html( $title ); ?></h4>
			<?php }
			$reading = new WP_Query( array(
				'posts_per_page' => 3,
				'meta_key'       => 'post_reading_count',
				'orderby'        => 'meta_value_num',
				'order'          => 'ASC'
			) );
			$counter = 1;
			if ( $reading->have_posts() ) : while ( $reading->have_posts() ) : $reading->the_post();
				$image_id = get_post_thumbnail_id( get_the_ID() ); ?>
                <div class="deva-widget-reading--item item-<?php echo esc_attr( $counter ); ?>">
                    <div class="deva-widget-reading--image">
						<?php if ( $image_id ) {
							$image_size = $counter === 1 ? 'large' : 'thumbnail';
							$image      = wp_get_attachment_image_url( $image_id, $image_size );
							$image_alt  = get_post_meta( $image_id, '_wp_attachment_image_alt', true ); ?>
                            <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>">
						<?php } ?>
                    </div>
                    <div class="deva-widget-reading--content">
						<?php if ( $counter === 1 ) { ?>
                            <div class="deva-widget-reading--top">
                                <div class="deva-widget-reading--categories top">
                                    <b><?php the_category( ' ' ); ?></b>
                                </div>
                                <span>
                                   <b>
                                       <i class="ion-clock"></i>
                                      <?php echo deva_reading_time( get_the_ID() ); ?>
                                   </b>
                                </span>
                            </div>
						<?php } else { ?>
                            <div class="deva-widget-reading--categories">
                                <b><?php the_category( ', ' ); ?></b>
                            </div>
						<?php } ?>
                        <a href="<?php the_permalink(); ?>"><h5><?php the_title(); ?></h5></a>
                        <div class="deva-widget-reading--footer">
                            <span><?php echo sprintf( esc_html__( '%s ago', 'deva' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
							<?php if ( $counter !== 1 ) { ?>
                                <span>
                                   <b>
                                       <i class="ion-clock"></i>
                                      <?php echo deva_reading_time( get_the_ID() ); ?>
                                   </b>
                                </span>
							<?php } ?>
                        </div>
                    </div>
                </div>
				<?php
				$counter ++;
			endwhile; endif;
			wp_reset_query();
			echo $args['after_widget'];
		}
		public function form( $instance ) {
			if ( isset( $instance['title'] ) ) {
				$title = $instance['title'];
			} else {
				$title = esc_html__( 'Short Reading', 'deva' );
			} ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'deva' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
                       name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
                       value="<?php echo esc_attr( $title ); ?>"/>
            </p>
			<?php
		}
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			return $instance;
		}
	}
}
if ( ! class_exists( 'deva_cat_posts' ) ) {
	class deva_cat_posts extends WP_Widget {
		function __construct() {
			parent::__construct(
				'deva_cat_posts',
				esc_html__( 'Deva Posts Categories', 'deva' ),
				array( 'description' => __( 'Posts Categories.', 'deva' ), )
			);
		}
		public function widget( $args, $instance ) {
			$title = apply_filters( 'widget_title', $instance['title'] );
			echo $args['before_widget'];
			if ( ! empty( $title ) ) { ?>
                <h4 class="widget-title"><?php echo esc_html( $title ); ?></h4>
			<?php }
			$categories = get_categories(); ?>
            <ul class="deva-widget-categories">
				<?php foreach ( $categories as $category ) {
					echo '<li><a href="' . get_category_link( $category->term_id ) . '">' . $category->name . ' (' . $category->count . ')</a></li>';
				} ?>
            </ul>
			<?php
			echo $args['after_widget'];
		}
		public function form( $instance ) {
			if ( isset( $instance['title'] ) ) {
				$title = $instance['title'];
			} else {
				$title = esc_html__( 'Categories', 'deva' );
			} ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'deva' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
                       name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
                       value="<?php echo esc_attr( $title ); ?>"/>
            </p>
			<?php
		}
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			return $instance;
		}
	}
}
function deva_register_widget() {
	if ( function_exists( 'is_woocommerce' ) ) {
		register_widget( 'deva_clear_all_widget' );
	}
	register_widget( 'deva_top_posts' );
	register_widget( 'deva_reading_posts' );
	register_widget( 'deva_cat_posts' );
}
add_action( 'widgets_init', 'deva_register_widget' );


if ( ! function_exists( 'deva_woocommerce_template_loop_product_title' ) ) {

	/**
	 * Show the product title in the product loop. By default this is an H2.
	 */
	function deva_woocommerce_template_loop_product_title() {

		$terms = get_the_terms( get_the_ID(), 'product_cat' );

		if ( count( $terms ) > 0 ) { ?>
            <div class="aheto-product__terms">

				<?php foreach ( $terms as $term ) { ?>

                    <a href="<?php echo esc_url( get_term_link( $term->term_id, 'product_cat' ) ); ?>"
                       class="aheto-product__terms-link"><?php echo esc_html( $term->name ); ?></a>

				<?php } ?>

            </div>
		<?php }


		echo '<h6 class="woocommerce-loop-product--title"><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h6>';
	}
}
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
add_action( 'woocommerce_shop_loop_item_title', 'deva_woocommerce_template_loop_product_title', 20 );


/**
 * Change number or products per row to 3
 */
add_filter( 'loop_shop_columns', 'deva_loop_columns', 999 );
if ( ! function_exists( 'deva_loop_columns' ) ) {
	function deva_loop_columns() {

		$view = empty( $_GET['view'] ) ? '' : wc_clean( wp_unslash( $_GET['view'] ) );

		return $view == 'list' ? 1 : 3; // 3 products per row
	}
}

if ( ! function_exists( 'deva_parse_url_product_view' ) ) {
	function deva_parse_url_product_view( $view ) {
		global $wp;
		$current_url   = home_url(add_query_arg(array(), $wp->request));
		$query         = $_GET;
		$query['view'] = $view;
		$query_result  = http_build_query( $query );

		return $current_url . '?' . $query_result;
	}
}


/**
 * Related product filter
 */

add_filter( 'woocommerce_product_related_products_heading', 'deva_product_related_products_heading', 999 );

if ( ! function_exists( 'deva_product_related_products_heading' ) ) {
	function deva_product_related_products_heading() {
		return esc_html__( 'Just for you', 'deva' );
	}
}

/**
 * Aheto dependency
 */

function deva_add_dependency( $id, $args = array(), $shortcode ) {

	if ( is_array( $id ) ) {

		foreach ( $id as $slug ) {

		    if(isset($shortcode->depedency[ $slug ]['template'])){
			    $param                                     = (array) $shortcode->depedency[ $slug ]['template'];
			    $shortcode->depedency[ $slug ]['template'] = array_merge( $args, $param );
            }
		}

	} else {
		if($shortcode->depedency[ $id ]['template']) {
			$param                                   = (array) $shortcode->depedency[ $id ]['template'];
			$shortcode->depedency[ $id ]['template'] = array_merge( $args, $param );
		}
	}

	return;
}

remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 11 );
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 12 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 11 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 12 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 13 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 14 );

add_image_size( 'deva-product-large', 700, 700, array( 'center', 'center' ) );
add_image_size( 'deva-post-large', 350, 350, true );


add_action( 'woocommerce_after_quantity_input_field', 'deva_product_short_item', 10, 0 );
function deva_product_short_item() {
	$product_short_info = get_post_meta( get_the_ID(), 'deva_product_short_items', true );

	if ( isset( $product_short_info ) && is_array( $product_short_info ) && count( $product_short_info ) > 0 ) { ?>

        <div class="deva-product-short-info">
			<?php foreach ( (array) $product_short_info as $key => $item ) { ?>

                <div class="deva-product-tab">
                    <input type="checkbox" id="short-info-<?php echo esc_attr( $key ); ?>">
                    <label class="deva-product-tab-label" for="short-info-<?php echo esc_attr( $key ); ?>">
						<?php if ( ! empty( $item['deva_short_image'] ) ) {
							$image_id = attachment_url_to_postid( $item['deva_short_image'] );
							echo wp_get_attachment_image( $image_id, 'full' );
						}

						echo wp_kses( $item['deva_short_title'], 'post' ); ?>
                    </label>
                    <div class="deva-product-tab-content">
						<?php echo wp_kses( $item['deva_short_text'], 'post' ); ?>
                    </div>

                </div>
			<?php } ?>
        </div>

	<?php }
}

if ( function_exists( 'aheto' ) ) {
	function deva_theme_options( $theme_tabs ) {

		$theme_tabs = [
			'deva_blog' => [
				'icon'  => 'dashicons dashicons-admin-generic',
				'title' => esc_html__( 'Blog List Options', 'deva' ),
				'desc'  => esc_html__( 'This tab contains the theme blog list options.', 'deva' ),
				'file'  => DEVA_T_PATH . '/include/blog-options.php',
			],
			'deva_post' => [
				'icon'  => 'dashicons dashicons-admin-generic',
				'title' => esc_html__( 'Blog Details Options', 'deva' ),
				'desc'  => esc_html__( 'This tab contains the theme blog details options.', 'deva' ),
				'file'  => DEVA_T_PATH . '/include/post-options.php',
			],
			'deva_shop' => [
				'icon'  => 'dashicons dashicons-admin-generic',
				'title' => esc_html__( 'Shop Options', 'deva' ),
				'desc'  => esc_html__( 'This tab contains the theme shop options.', 'deva' ),
				'file'  => DEVA_T_PATH . '/include/shop-options.php',
			],
		];

		return $theme_tabs;
	}
}

add_filter( 'aheto_theme_options', 'deva_theme_options', 10, 2 );

if ( ! function_exists( 'deva_reading_time' ) ) {
	function deva_reading_time( $post_id ) {
		$content = get_post_field( 'post_content', $post_id );

		$word_count       = str_word_count( strip_tags( $content ) );
		$readingtime      = ceil( $word_count / 200 );
		$totalreadingtime = $readingtime . ' min';

		$countKey = 'post_reading_count';
		$count    = get_post_meta( $post_id, $countKey, true );
		if ( $count == '' ) {
			delete_post_meta( $post_id, $countKey );
			add_post_meta( $post_id, $countKey, $totalreadingtime );
		} else {
			update_post_meta( $post_id, $countKey, $totalreadingtime );
		}

		return $totalreadingtime;
	}
}


/*
 * Set post views count using post meta
 */
if ( ! function_exists( 'deva_set_post_views' ) ) {
	function deva_set_post_views( $postID ) {
		$countKey = 'post_views_count';
		$count    = get_post_meta( $postID, $countKey, true );
		if ( $count == '' ) {
			$count = 0;
			delete_post_meta( $postID, $countKey );
			add_post_meta( $postID, $countKey, '0' );
		} else {
			$count ++;
			update_post_meta( $postID, $countKey, $count );
		}
	}
}


function deva_export_data() {
	if(class_exists('Aheto\Template_Kit\API') ){

		$aheto_api = new Aheto\Template_Kit\API;

		$endpoint = '/aheto/v1/getThemeTemplate/9620';

		$response = $aheto_api->get_demodata($endpoint, false, false);
		return $response;
	}
}

add_filter( 'export_data', 'deva_export_data', 10 );


add_filter( 'aheto_template_kit_category', function() {
	return 'deva';
} );

add_filter( 'aheto_wizard', function () {
	return true;
} );

//file download after submit

function download(){
    ?>
    <script type="text/javascript">
function SaveToDisk(fileURL, fileName) {
    // for non-IE
    if (!window.ActiveXObject) {
        var save = document.createElement('a');
        save.href = fileURL;
        save.target = '_blank';
        save.download = fileName || 'unknown';

        var evt = new MouseEvent('click', {
            'view': window,
            'bubbles': true,
            'cancelable': false
        });
        save.dispatchEvent(evt);

        (window.URL || window.webkitURL).revokeObjectURL(save.href);
    }

    // for IE < 11
    else if ( !! window.ActiveXObject && document.execCommand)     {
        var _window = window.open(fileURL, '_blank');
        _window.document.close();
        _window.document.execCommand('SaveAs', true, fileName || fileURL)
        _window.close();
    }
}


     var downloadURL = 'http://127.0.0.1/wp-content/uploads/2023/04/【ICHIISOFT社】会社のウェブサイト内容_v1.2-1.pptx';   
        document.addEventListener( 'wpcf7mailsent', function( event ) {
if ( '3832' == event.detail.contactFormId ) {

var m = /[^/]*$/.exec(downloadURL)[0];
SaveToDisk(downloadURL, m);
}
}, false );
    </script>
    <?php
}
add_action('wp_footer', "download");

// function display_posts_grid($atts) {
//     global $wpdb;
// $post_id = 123; // Replace 123 with the ID of the post you want to retrieve
// 	$content = $wpdb->get_var(
// 		$wpdb->prepare(
// 			"SELECT post_content FROM {$wpdb->posts} WHERE ID = %d",
// 			$post_id
// 		)
// 		);
// 		echo $content;
//    // Get shortcode attributes
//     extract(shortcode_atts(array(
//         'category' => 'Laravel',
//         'posts_per_page' => '6'
//     ), $atts));

//     // Query posts based on category
//     $args = array(
//         'post_type' => 'post',
//         'posts_per_page' => $posts_per_page,
//         'category_name' => $category,
//         'orderby' => 'date',
//         'order' => 'DESC',
// 		'content'=>''
//     );
//     $query = new WP_Query($args);
    
//     // Output posts in a grid
//     $output = '';
//     if ($query->have_posts()) {
//         $output .= '<div class="posts-grid">';
//         while ($query->have_posts()) {
//             $query->the_post();
//             $output .= '<div class="post-grid-item">';
//             $output .= '<a href="' . get_permalink() . '">';
//             $output .= get_the_post_thumbnail();
//             $output .= '<h2>' . get_the_title() . '</h2>';
//             $output .= '</a>';
//             $output .= '</div>';
//         }
//         $output .= '</div>';
//     }
//     wp_reset_postdata();
//     return $output;
// }

function display_posts_grid($atts) {
	
    // Get shortcode attributes
    extract(shortcode_atts(array(
        'category' => 'Technology',
        'posts_per_page' => '6'
    ), $atts));

    $max_chars = 200; // Set the maximum number of characters to display
    $max_chars_title = 15; // set max for content title
    // Query posts based on category
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $posts_per_page,
        'category_name' => $category,
		'orderby' => 'date',
        'order' => 'DESC'
    );
    $query = new WP_Query($args);
     
    // Output posts in a grid
    $output = '';
    $output .= '<style>
        /* Style for the posts grid container */
        .posts-grid {
			font-family: "Roboto";
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            grid-gap: 20px;
        }

        /* Style for each post grid item */
        .post-grid-item {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
			transition: background-color 0.3s ease-in-out;
        }
        
		.post-grid-item:hover {
            cursor: pointer;
            box-shadow: 0 4px 6px #00B0509C;
        }
        /* Style for the post title */
        .post-grid-item h2 {
			font-weight: 700;
            margin-top: 10px;
            margin-bottom: 10px;
            font-size: 2em;
			transition: background-color 0.3s ease-in-out;
        }
        
		.post-grid-item h2:hover{
			color: #00B0509C;
		}
        /* Style for the post content */
        .post-content {
            margin-top: 10px;
            font-size: 1em;
            line-height: 1.5;
        }

        /* Style for the post thumbnail */
        .post-grid-item img {
	        border-radius: 10px;
            max-width: 100%;
            height: auto;
        }

        /* Responsive styles */
        @media screen and (max-width: 600px) {
            .posts-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                grid-gap: 10px;
            }
        }
    </style>';
     // Output posts in an Owl Carousel container
	echo '<div class="owl-carousel">';
    if ($query->have_posts()) {
        $output .= '<div class="posts-grid">';
        while ($query->have_posts()) {
            $query->the_post();
            $output .= '<div class="post-grid-item">';
            $output .= '<a href="' . get_permalink() . '">';
            $output .= get_the_post_thumbnail();
            $output .= '<h2>' . $content_title = mb_substr( get_the_title(), 0, 25) . '</h2>'; // giới hạn ký tự content cho tieu de bai viết trong wordpress
            $output .= '</a>';
			$output .= '<div class="post-content">' . substr(get_the_content(), 0, $max_chars) . '...' . '</div>';
            $output .= '</div>';
        }
        $output .= '</div>';
    }
	echo '</div>';
    wp_reset_postdata();
	 
    return $output;
	
    // Initialize Owl Carousel with JavaScript
    ?>
    <script>
    jQuery(document).ready(function($) {
        $(".owl-carousel").owlCarousel({
            items: 1,
            loop: true,
            autoplay: true,
            autoplayTimeout: 3000, // Change this number to adjust the time between rotations
            autoplayHoverPause: true
        });
    });
    </script>
	<?php

}
add_shortcode('posts_grid', 'display_posts_grid');
add_action( 'wp_enqueue_scripts', 'display_posts_grid' );
// list  hiển thị slide carousel for bài đăng mới nhất our work




// [posts_grid_our_work category="AWS" posts_per_page="12"]

function display_posts_grid_our_work($atts) {

    // Get shortcode attributes
    extract(shortcode_atts(array(
        'category' => '',
        'posts_per_page' => ''
    ), $atts));

    $max_chars = 100; // Set the maximum number of characters to display
    $max_chars_title = 60; // set max for content title
    // Query posts based on category
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $posts_per_page,
        'category_name' => $category,
        'orderby' => 'date',
        'order' => 'DESC'
    );
    $query = new WP_Query($args);
    // Output posts in a grid
    $output = '';
    $output .= '<style>

    /* Style for the posts grid container */
    .posts-grid-our-work {
        font-family: "Roboto";
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        grid-gap: 20px;
    }

    /* Style for each post grid item */
    .post-grid-item-our-work {
        background-color: #ffffff;
        padding: 0px;
        border-radius: 10px;
        border: 1px solid #C7C7C7;
        transition: background-color 0.3s ease-in-out;
    }

    .post-grid-item-our-work:hover {
        cursor: pointer;
        box-shadow: 0 4px 6px #00B0509C;
    }

    /* Style for the post title */
    .post-grid-item-our-work h2 {
        padding: 10px;
        font-weight: 700;
        margin-top: 10px;
        margin-bottom: 10px;
        font-size: 1.5em;
        line-height: 20px;
        transition: background-color 0.3s ease-in-out;
    }

    .post-grid-item-our-work h2:hover {
        color: #00B0509C;
    }

    /* Style for the post thumbnail */
    .post-grid-item-our-work img {
        border-radius: 10px 10px 0px 0px;
        max-width: 100%;
        height: auto;
    }

    .post-categories {
        padding: 0px 10px 10px 10px;
    }

    .post-categories a {
        color: #666666;
        font-family: "Roboto";
        font-weight: 400;
        font-size: 14px;
        border-radius: 100px;
        border: 1px solid #E0E0E0;
        padding: 5px 10px 5px 10px;
        transition: background-color 0.3s ease-in-out;
    }

    .post-categories a:hover {
        border: 1px solid #00B0509C;
    }

    /* Responsive styles */
    @media screen and (max-width: 600px) {
        .posts-grid-our-work {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            grid-gap: 10px;
        }
    }

</style>';
    if ($query->have_posts()) {
        $output .= '<div class="posts-grid-our-work">';
        while ($query->have_posts()) {
            $query->the_post();
            $output .= '<div class="post-grid-item-our-work">';
            $output .= '<a href="' . get_permalink() . '">';
            $output .= get_the_post_thumbnail();
            $output .= '<h2>' . $content_title = mb_substr( get_the_title(), 0, $max_chars_title) . '</h2>'; // giới hạn ký tự content cho tieu de bai viết trong wordpress
            $output .= '</a>';
			$categories = get_the_category(); // Lấy danh sách các danh mục của bài viết
            if (!empty($categories)) {
                $output .= '<div class="post-categories">';
                foreach ($categories as $category) {
                    $output .= '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>';
                    if ($category !== end($categories)) {
                        $output .= ' ';
                    }
                }
                $output .= '</div>';
            }
            $output .= '</div>';
        }
        $output .= '</div>';
    }
    wp_reset_postdata();

    return $output;

}
add_shortcode('posts_grid_our_work', 'display_posts_grid_our_work');
// hien thi job list

function show_list_job(){
    ob_start();
    ?>

<style>
    .container-job-list{
      display: flex;
      flex-direction: column;
      padding: 30px 0px 0px;
      max-width: 1240px;
	  font-family: 'Roboto';
	  font-size: 16px;
	  line-height: 24px;
   }
   .job-list-option-row{
      display: flex;
      flex-direction: row;
      align-items: flex-start;
      padding: 0px 20px;
	  font-family: 'Roboto';
	  font-style: normal;
	  font-weight: 700;
	  color: #000000;
   }
   .option-col{
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      padding: 10px;
      width: 292.5px;
     
   }
   .job-list-data-row{
	  margin-top: 10px;
      box-sizing: border-box;
      display: flex;
      flex-direction: row;
      align-items: center;
      padding: 10px 20px 10px 20px;
      height: 85px;
      background: #FFFFFF;
      border: 1px solid #DADADA;
      border-radius: 10px;
   }
   .list-data-col{
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      padding: 10px;
      width: 295px;
   }
   .list-data-col a:hover{
	color: #00B0509C;
   }
  .btn-apply {
	  font-family: 'Roboto';
      cursor: pointer;
      text-align: center;
      border-radius: 7px;
      background: #333333;
      width: 150px;
      height: 45px;
      color: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      text-decoration: none;
	  font-weight: 700;
	  line-height: 19px;
    }
   @media (max-width: 768px) {
  .container-job-list {
    width: 100%;
    margin: 0 auto;
    height: auto;
  }
  .job-list-option-row {
    align-items: center;
    padding: 15px 0;
  }
  .option-col {
    width: 100%;
    text-align: center;
    padding: 5px;
    
  }
  .job-list-data-row {
    align-items: center;
    padding: 20px 0;
    height: auto;
  }
  .list-data-col {
    padding: 5px;
    width: 100%;
    text-align: center;
    
  }
   .btn-apply {
      cursor: pointer;
      text-align: center;
      background: #333333;
      width: 100%;
      height: 40px;
      color: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      text-decoration: none;
    }
}
	@media (min-width: 768px) and (max-width: 1024px) {
	.container-job-list {
		/* width: 90%;
		padding: 30px 20px 0; */
		height: auto;
		margin: auto;
	}
	.job-list-option-row {
		padding: 15px;
	}
	.option-col {
		width: 33.33%;
		
	}
	.job-list-data-row {
		width: 100%;
		margin-bottom: 20px;
	
	}
	.list-data-col {
		padding: 5px;
		width: 33.33%;
	
	}
	}

	@media (min-width: 1024px) {
	.container-job-list {
		width: 100%;
		margin: auto;
	}
	.option-col {
		width: 292.5px;
	}
	}
    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .page-numbers {
		font-weight: 700;
    	font-family: 'Roboto';
		width: 32px;
		height: 32px;
        padding: 5px 10px;
        margin: 0 5px;
        border: 1px solid #ddd;
        border-radius: 5px;
        cursor: pointer;
    }
	.pagination a:hover{
		color: #00B0509C;
	}

    .page-numbers.current {
        background-color: #333;
        color: #fff;
    }


</style>
    <?php
    global $wpdb;
    $limit = 4; // So trang can paginate
    $total = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}job" );
    $num_of_pages = ceil( $total / $limit );
    $pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
    $offset = ( $pagenum - 1 ) * $limit;
    $table_name = $wpdb->prefix . 'job';
    $qry = "SELECT * FROM $table_name order by id desc LIMIT $offset, $limit";
    $result = $wpdb->get_results( $qry, ARRAY_A );

    if ( $result ) :
		?>

			<div class="container-job-list">
					<div class="job-list-option-row">
						<div class="option-col">Position</div>
						<div class="option-col">Location</div>
						<div class="option-col">Type</div>
						<div class="option-col"></div>
					</div>
		<?php foreach ( $result as $job ) : ?>
					<div class="job-list-data-row">
						<div class="list-data-col"><?php echo $job['job_position']; ?></div>
						<div class="list-data-col"><?php echo $job['job_location']; ?></div>
						<div class="list-data-col"><?php echo $job['job_type']; ?></div>
						<div class="list-data-col">
							<a href="<?php echo esc_url( home_url( '/recruitment-detail?id=' . $job['id'] ) ); ?>" data-job-id="<?php echo $job['id']; ?>"  class="btn-apply">Apply now</a>
						</div>
					</div>
		<?php
		endforeach;
		?>
		<div class="pagination">
			<?php
			for ( $i = 1; $i <= $num_of_pages; $i++ ) {
				$class = ( $pagenum == $i ) ? 'current' : '';
				?>
				<a class="page-numbers <?php echo $class; ?>" href="<?php echo esc_url( add_query_arg( 'pagenum', $i ) ); ?>"><?php echo $i; ?></a>
				<?php
			}
			?>
		</div>
		<?php
	endif;

    return ob_get_clean();
}
add_shortcode( 'my_list_job', 'show_list_job' );


// short code để  hiển thị chi tiết công việc cho page recuiment detail



add_shortcode( 'job_detail', 'job_detail_shortcode' );
function job_detail_shortcode( $atts ) {
	// $atts = shortcode_atts( array(
    //     'id' => '',
    // ), $atts );
    ?>
	<style>
        .container-job-sidebar{
			flex-direction: row;
			font-family: "Roboto";
			box-sizing: border-box;
			/* width: 360px; */
			height: auto;
			border: 1px solid #DBDBDB;
			padding: 15px;
			gap: 20px;
			border-radius: 5px;
		}
			.container-job-sidebar .job-sidebar-content h3{
			color: #333333;
			font-family: "Roboto";
			font-weight: 700;
			font-size: 18px;
			line-height: 21.09px;
			margin-bottom: 5px;
		}
		.container-job-sidebar .job-sidebar-content .job-local,
		.container-job-sidebar .job-sidebar-content .job-type {
			display: inline-block;
			font-size: 18px;
			line-height: 19px;
		}
		.container-job-sidebar .job-sidebar-content button {
			color: #ffffff;
			display: flex;
			flex-direction: row;
			justify-content: center;
			align-items: center;
			padding: 20px 30px;
			gap: 14.2px;
			width: 150px;
			height: 45px;
			background: #333333;
			box-shadow: 0px 4px 5px rgba(0, 0, 0, 0.1);
			border-radius: 7px;
			margin-top: 5px;
			font-weight: 700;

		}
		@media screen and (min-width: 768px) and (max-width: 991px) {
			.container-job-sidebar {
				max-width: 100%;
		}
		
		.container-job-sidebar .job-sidebar-content {
			margin-left: 20px;
		}
		}
	</style>
	<?php
    global $wpdb;
    $table_name = $wpdb->prefix . 'job';
    $qry = "SELECT * FROM $table_name WHERE id = %d";
	$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : "";
    $result = $wpdb->get_row( $wpdb->prepare( $qry, $id ), ARRAY_A );
	
    if ( $result ) {
        ob_start();
        ?>
		<div class="container-job-sidebar">
			<div class="job-sidebar-content">
				<h3><?php echo $result['job_title']?></h3>
				<div class="job-local"><b>Local: </b><?php echo $result['job_location']; ?></div>
				<div class="job-type"><b> <?php echo ' &ensp;';?>Type: </b><?php echo $result['job_type']; ?></div>
				<button>Apply</button>
			</div>
		</div>
        <?php
        return ob_get_clean();
    } else {
        return 'Không tìm thấy việc làm';
    }
}

//hiển thị chi tiết job detail trang nội dung chính không phải sidebar
add_shortcode( 'job_detail-contents', 'job_detail_contents_shortcode' );
function job_detail_contents_shortcode( $atts ) {
	// $atts = shortcode_atts( array(
    //     'id' => '',
    // ), $atts );
    ?>
	<style>
         .container-job {
			font-family: "Roboto";
			max-width: 820px;
			margin: 0 auto;
		}

		.job-list-title h2 {
			font-size: 24px;
			line-height: 28px;
			font-weight: 700;
			margin-bottom: 10px;
		}

		.job-list-title p span {
			font-size: 14px;
			line-height: 16px;
			color: #999;
		}

		
		.job-details-price{
			font-weight: 700;
			font-size: 20px;
			line-height: 18px;
			color: #C0504D;
		}

		.job-details-expires{
			font-weight: 400;
			font-size: 16px;
			line-height: 19px;
		}

		.job-list-details ul {
			margin-left: 15px;
			list-style: none;
			margin: 0;
			padding: 0;
		}

		.job-list-details li {
			font-size: 16px;
			line-height: 24px;
			color: #333333;
			margin-bottom: 5px;
		}

		.job-list-details li strong {
			font-weight: 700;
		}

		.job-list-details button {
			background-color: #C0504D;
			border: none;
			color: white;
			padding: 10px 20px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 16px;
			margin-top: 10px;
			cursor: pointer;
			border-radius: 5px;
			font-weight: 700;
		}

		.content-job {
			margin-top: 15px;
			background-color: #FFF;
			border: 1px solid #DBDBDB;
			border-radius: 5px;
			padding: 15px;
		}

		.content-job ul {
			list-style: disc inside;
			margin-left: 15px;
		}

		.content-job p strong {
			font-weight: 700;
			font-size: 18px;
			line-height: 21px;
			color: #333333;
			margin-bottom: 10px;
		}

		.job-list li span {
			font-size: 16px;
			line-height: 24px;
			color: #333333;
		}

		.job-list li {
			margin-bottom: 10px;
		}
		
		/* Cho màn hình desktop */
		@media screen and (min-width: 1024px) {
			.container-job {
			max-width: 1024px;
			}
			.job-list-details {
			display: block;
			align-items: center;
			justify-content: space-between;
			}
			.job-list-details ul {
			width: 100%;
			}
			.content-job {
			margin-top: 30px;
			}
		}

		/* Cho màn hình tablet */
		@media screen and (max-width: 1023px) and (min-width: 768px) {
			.container-job {
			max-width: 768px;
			}
			.job-list-details {
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			}
			.job-list-details ul {
			width: 100%;
			}
			.content-job {
			margin-top: 20px;
			}
		}

		/* Cho màn hình điện thoại */
		@media screen and (max-width: 767px) {
			.container-job {
			max-width: 100%;
			}
			.job-list-title h2 {
			font-size: 20px;
			line-height: 24px;
			}
			.job-list-title p span {
			font-size: 12px;
			line-height: 14px;
			}
			.job-list-details {
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			}
			.job-list-details ul {
			width: 100%;
			}
			.content-job {
			margin-top: 20px;
			padding: 10px;
			}
			.content-job p strong {
			font-size: 16px;
			line-height: 18px;
			margin-bottom: 8px;
			}
			.job-list li span {
			font-size: 14px;
			line-height: 20px;
			}
			.job-list li {
			margin-bottom: 8px;
			}
			.job-list-details button {
			margin-top: 15px;
			}
		}
	</style>
	<?php
    global $wpdb;
    $table_name = $wpdb->prefix . 'job';
    $qry = "SELECT * FROM $table_name WHERE id = %d";
	$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : "";
    $result = $wpdb->get_row( $wpdb->prepare( $qry, $id ), ARRAY_A );
	
    if ( $result ) {
        ob_start();
        ?>
		  <div class="container-job">
			<div class="job-list-title">
				<h2><?php echo $result['job_title']?></h2>
				<p><span><?php
								// Lấy thời gian hiện tại
								$current_time = new DateTime();
								// Lấy giá trị của trường `job_date`
								$job_date_post = $result['job_date'];
								$job_time = new DateTime($job_date_post);
								// Tính khoảng thời gian chênh lệch giữa hai thời điểm
								$interval = $current_time->diff($job_time);
								// Lấy số ngày, giờ, phút và giây chênh lệch
								$days = $interval->days;
								$hours = $interval->h;
								$minutes = $interval->i;
								$seconds = $interval->s;
								// Tính tổng số phút chênh lệch
								// $total_minutes = $interval->days * 24 * 60 + $interval->h * 60 + $interval->i;
								echo $days .' ngày ' . $hours . ' giờ ' . $minutes . ' phút ' . $seconds . ' giây '?> trước
					</span></p>
			</div>
			<div class="job-list-details">
			
				<ul class="job-list-details-content">
				<p class="job-details-price"><?php echo $result['job_salary']?>$</p>
				<p class="job-details-expires">Expires after: <?php 
											// Lấy thời gian hiện tại
											$current_time_expires = new DateTime();
											// Lấy giá trị của trường `job_date`
											$job_date__expires = $result['job_expires'];
											$job_time_expires = new DateTime($job_date__expires);
											// Tính khoảng thời gian chênh lệch giữa hai thời điểm
											$interval_expires = $current_time_expires->diff($job_time_expires);
											// Tính tổng số ngày chênh lệch
											$total_days = $interval_expires->format('%a');
                                            echo $total_days;
								?> days</p>
				<li>
					<span>Job Name: </span>
					<strong> <?php echo $result['job_title']?></strong>
				</li>
				<li>
					<span>Work Location: </span>
					<strong> <?php echo $result['job_location']?> </strong>
				</li>
				<li>
					<span>Number of applicants: </span>
					<strong> <?php echo $result['job_number_of_application']?></strong>
				</li>
				<li>
					<span>Job Type: </span>
					<strong> <?php echo $result['job_type']?></strong>
				</li>
				<li>
					<span>Salary: </span>
					<strong> <?php echo $result['job_salary']?> JPY/year</strong>
				</li>
				<li>
					<button>Apply now</button>
				</li>
				</ul>
			</div>
			<div class="content-job">
				<p><strong>Job Description</strong></p>
				<ul class="job-list">
				<li><span> <?php echo $result['job_description']?> </span></li>
				<!-- <li><span>Develop and execute sales plan; Building, managing and developing customer relationships in Japan;</span></li>
				<li><span>Assess the needs of customers to use IT Software to propose suitable products and services;</span></li>
				<li><span>Prepare documents, meet customers and introduce products and services; </span></li>
				<li><span>Business negotiation and signing long-term contracts with customers, after-sales technical consulting;</span></li>
				<li><span>Analyze &amp; forecast customer and market situation, propose necessary countermeasures in a timely manner; </span></li>
				<li><span>Attend trade shows and IT industry events to learn about products and connect with customers. </span></li> -->
				</ul>
			</div>
			<div class="content-job">
				<p><strong>Job Requirements</strong></p>
				<ul class="job-list">
				<li><span><?php echo $result['job_requirements']?> </span></li>
				<!-- <li><span>Develop and execute sales plan; Building, managing and developing customer relationships in Japan;</span></li>
				<li><span>Assess the needs of customers to use IT Software to propose suitable products and services;</span></li>
				<li><span>Prepare documents, meet customers and introduce products and services; </span></li>
				<li><span>Business negotiation and signing long-term contracts with customers, after-sales technical consulting;</span></li>
				<li><span>Analyze &amp; forecast customer and market situation, propose necessary countermeasures in a timely manner; </span></li>
				<li><span>Attend trade shows and IT industry events to learn about products and connect with customers. </span></li> -->
				</ul>
			</div>
			<div class="content-job">
				<p><strong>Benefits</strong></p>
				<ul class="job-list">
				<li><span><?php echo $result['job_benefits']?> </span></li>
				<!-- <li><span>Develop and execute sales plan; Building, managing and developing customer relationships in Japan;</span></li>
				<li><span>Assess the needs of customers to use IT Software to propose suitable products and services;</span></li>
				<li><span>Prepare documents, meet customers and introduce products and services; </span></li>
				<li><span>Business negotiation and signing long-term contracts with customers, after-sales technical consulting;</span></li>
				<li><span>Analyze &amp; forecast customer and market situation, propose necessary countermeasures in a timely manner; </span></li>
				<li><span>Attend trade shows and IT industry events to learn about products and connect with customers. </span></li> -->
				</ul>
			</div>
		</div>
        <?php
        return ob_get_clean();
    } else {
        return 'Không tìm thấy nội dung chi tiết việc làm.';
    }
}




//xu ly button modal gui mail

function send_email_modal_shortcode() {
    ob_start(); ?>

	<style>

		.modal {
			display: flex;
			flex-direction: column;
			justify-content: center;
			gap: 0.4rem;
			width: 450px;
			padding: 1.3rem;
			min-height: 250px;
			position: fixed;
			top: 50% !important; /* căn giữa theo trục y */
			left: 50%; /* căn giữa theo trục x */
			transform: translate(-50%, -50%); /* dịch chuyển để căn giữa */
			top: 20%;
			background-color: white;
			border: 1px solid #ddd;
			border-radius: 15px;
		    z-index: 9999 !important;
			}

			/* Style cho màn hình desktop */
			@media only screen and (min-width: 1024px) {
        .modal {
            width: 450px;
        }
    }

    /* Style cho màn hình tablet */
    @media only screen and (min-width: 768px) and (max-width: 1023px) {
        .modal {
            width: 350px;
        }
    }

    /* Style cho màn hình mobile */
    @media only screen and (max-width: 767px) {
        .modal {
            width: 350px;
        }
        .modal input[type=text], input[type=password],
        input[type=email], input[type=tel],
        input[type=file], textarea {
            width: 100%;
        }
    }

			.modal .flex {
			display: flex;
			align-items: center;
			justify-content: space-between;
			}

			.title-modal h3{
			position: relative;
			display: flex;
			color: #C0504D;
			font-size: 24px;
			margin-bottom: 20px;
			}
			.modal .flex span{
			font-size: 16px;
			position: relative;
			display: flex;
			}

			.modal input[type=text], input[type=password],
			input[type=email], input[type=tel],
			input[type=file], textarea
			{
			margin-top: 0.4rem;
			padding: 0.7rem 1rem;
			border: 1px solid #ddd;
			border-radius: 5px;
			font-size: 0.9em;
			width:100%;
			}

			.modal p {
			font-size: 0.9rem;
			color: #777;
			margin: 0.4rem 0 0.2rem;
			}

			button {
			cursor: pointer;
			border: none;
			font-weight: 600;
			}

			.btn {
			display: inline-block;
			padding: 0.8rem 1.4rem;
			font-weight: 700;
			background-color: black;
			color: white;
			border-radius: 5px;
			text-align: center;
			font-size: 1em;
			width: 100%;
			
			}

			.btn-open {
			display: inline-block;
			padding: 0.8rem 1.4rem;
			font-weight: 700;
			background-color: black;
			color: white;
			border-radius: 5px;
			text-align: center;
			font-size: 1em;
			position: absolute;
			background-color: black;
			}

			.btn-close {
			transform: translate(10px, -20px);
			padding: 0.5rem 0.7rem;
			background: #eee;
			border-radius: 50%;
			}
			.overlay {
			position: fixed;
			top: 0;
			bottom: 0;
			left: 0;
			right: 0;
			width: 100%;
			height: 100%;
			background: rgba(0, 0, 0, 0.5);
			backdrop-filter: blur(3px);
			z-index: 1;
			}
			.modal {
			z-index: 2;
			}
			.hidden {
			display: none;
			}
			textarea {
			padding: 0.7rem 1rem;
			}
	</style>

   

	<div class="modal hidden" id="email-modal">
	
		<div class="flex">
			<span> Hello!</span>
			<button class="btn-close">⨉</button>
		</div>
		<div class="title-modal">
			<h3>Join the ichiisoft</h3>
		</div>
		<form method="post" enctype="multipart/form-data">
		<input type="text" name="name" placeholder="Your name" required />
		<input type="email" name="email" placeholder="Your email" required/>
		<input type="tel" name="phone" placeholder="Your phone number" required/>
		<input type="file" name="file" placeholder="Upload CV" />
		<textarea id="content" name="message" rows="8" cols="50" placeholder="Content" required></textarea>
		<button class="btn" name="submit">Apply</button>
		</form>
	</div>

		<div class="overlay hidden"></div>
		<button id="send-email" class="btn-open">App now</button>

    <script>

        jQuery(document).ready(function($) {
            // Hiển thị modal khi nhấn nút
            // $('#send-email').click(function() {
            //     $('#email-modal').show();
            // });

            // Xử lý khi người dùng gửi biểu mẫu
            $('#email-modal form').submit(function(e) {
                e.preventDefault();

                // Lấy thông tin từ biểu mẫu
                var name = $('#email-modal input[name="name"]').val();
                var email = $('#email-modal input[name="email"]').val();
				var phone = $('#email-modal input[name="phone"]').val();
                var message = $('#email-modal textarea[name="message"]').val();

                // Kiểm tra xem các trường có được điền đầy đủ
                if (name === '' || email === '' || phone === '' || message === '') {
                    alert('Vui lòng điền đầy đủ thông tin.');
                    return false;
                }

                // Gửi email bằng AJAX
                $.post('<?php echo admin_url('admin-ajax.php'); ?>', {
                    action: 'send_email',
                    name: name,
                    email: email,
					phone: phone,
                    message: message,
                }, function(response) {
                    alert(response);
                    // $('#email-modal').hide();
                });

                return false;
            });
        });
    </script>
	<script>
		const modal = document.querySelector(".modal");
		const overlay = document.querySelector(".overlay");
		const openModalBtn = document.querySelector(".btn-open");
		const closeModalBtn = document.querySelector(".btn-close");
		
		const openModal = function () {
		modal.classList.remove("hidden");
		overlay.classList.remove("hidden");
		};

		openModalBtn.addEventListener("click", openModal);

		const closeModal = function () {
		modal.classList.add("hidden");
		overlay.classList.add("hidden");
		};

		closeModalBtn.addEventListener("click", closeModal);

		overlay.addEventListener("click", closeModal);

		document.addEventListener("keydown");
		document.addEventListener("keydown", function (e) {
		if (e.key === "Escape" && !modal.classList.contains("hidden")) {
			closeModal();
		}
		});
	</script>
    <?php
    return ob_get_clean();
}
add_shortcode('send_email_modal', 'send_email_modal_shortcode');

// Xử lý khi gửi email bằng AJAX
add_action('wp_ajax_send_email', 'send_email_ajax_handler');
add_action('wp_ajax_nopriv_send_email', 'send_email_ajax_handler');
function send_email_ajax_handler() {
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
	$phone = sanitize_text_field($_POST['phone']);
    $message = sanitize_textarea_field($_POST['message']);
    
    // Kiểm tra xem các trường có được điền đầy đủ
    if (empty($name) || empty($email) || empty($message)) {
        echo 'Vui lòng điền đầy đủ thông tin.';
    } else {
        // Gửi email
        $to = 'yuntips1702@gmail.com';
        $subject = 'Tin nhắn mới từ ' . $name;
        $body = 'Name: ' . $name . "\n\n" . 'Email: ' . $email . "\n\n" . 'Phone: ' . $phone . "\n\n" . 'Tin nhắn: ' . $message;

        $headers = array('From: ' . $name . ' <' . $email . '>');
        wp_mail($to, $subject, $body, $headers);

        // Trả về thông báo khi gửi email thành công
        echo 'Cảm ơn bạn đã gửi tin nhắn.';
    }
    wp_die();
}





function send_email_with_attachment_callback() {
    $html = '';

    // Build form HTML
    $html .= '<form id="send-email-form" enctype="multipart/form-data">';
    $html .= wp_nonce_field('send_email_with_attachment', 'send_email_with_attachment_nonce', true, false);
    $html .= '<div class="form-group">';
    $html .= '<label for="name">Name:</label>';
    $html .= '<input type="text" name="name" id="name" class="form-control" required>';
    $html .= '</div>';
    $html .= '<div class="form-group">';
    $html .= '<label for="email">Email:</label>';
    $html .= '<input type="email" name="email" id="email" class="form-control" required>';
    $html .= '</div>';
    $html .= '<div class="form-group">';
    $html .= '<label for="message">Message:</label>';
    $html .= '<textarea name="message" id="message" class="form-control" required></textarea>';
    $html .= '</div>';
    $html .= '<div class="form-group">';
    $html .= '<label for="attachment">Attachment:</label>';
    $html .= '<input type="file" name="attachment" id="attachment" class="form-control-file" required>';
    $html .= '</div>';
    $html .= '<button type="submit" class="btn btn-primary">Submit</button>';
    $html .= '</form>';

    // Add AJAX script
    $html .= '<script>';
    $html .= 'jQuery("#send-email-form").submit(function(event) {';
    $html .= 'event.preventDefault();';
    $html .= 'var formData = new FormData(this);';
    $html .= 'jQuery.ajax({';
    $html .= 'type: "POST",';
    $html .= 'url: "' . admin_url('admin-ajax.php') . '",';
    $html .= 'data: formData,';
    $html .= 'contentType: false,';
    $html .= 'processData: false,';
    $html .= 'success: function(data) {';
    $html .= 'jQuery("#send-email-form").replaceWith(data);';
    $html .= '}';
    $html .= '});';
    $html .= '});';
    $html .= '</script>';

    return $html;
}
add_shortcode('send_email_with_attachment', 'send_email_with_attachment_callback');

// Add AJAX endpoint for form submission
function send_email_with_attachment_ajax_handler() {
    $response = array('success' => false, 'message' => '');

    // Verify nonce
    if (!isset($_POST['send_email_with_attachment_nonce']) || !wp_verify_nonce($_POST['send_email_with_attachment_nonce'], 'send_email_with_attachment')) {
        $response['message'] = 'Invalid nonce.';
    } else {
        // Get form data
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $message = wp_kses_post($_POST['message']);
        $file = $_FILES['attachment'];

        // Validate input
        if (!$name || !$email || !$message || !$file) {
            $response['message'] = 'Please fill in all fields and choose a file.';
        } else {
            // Save file to directory
            $directory = wp_upload_dir()['path'];
            $file_name = md5(basename($file['name']) . time()) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
            $file_path = $directory . '/' . $file_name;
            move_uploaded_file($file['tmp_name'], $file_path);

            // Get email headers
            $headers = array(
                'From: ' . $name . ' <' . $email . '>',
                'Content-Type: multipart/mixed; charset=UTF-8; boundary="' . md5(time()) . '"',
            );

            // Get attachment data
            $file_content = file_get_contents($file_path);
            $file_encoded = chunk_split(base64_encode($file_content));

            // Build email message
            $message_body = "--" . md5(time()) . "\r\n";
            $message_body .= "Content-Type: text/plain; charset=UTF-8\r\n";
            $message_body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
            $message_body .= "Name: $name\nEmail: $email\nMessage: $message\n\n";
            $message_body .= "--" . md5(time()) . "\r\n";
            $message_body .= "Content-Type: application/octet-stream; name=\"$file_name\"\r\n";
            $message_body .= "Content-Transfer-Encoding: base64\r\n";
            $message_body .= "Content-Disposition: attachment; filename=\"$file_name\"\r\n\r\n";
            $message_body .= $file_encoded . "\r\n";
            $message_body .= "--" . md5(time()) . "--";

            // Send email
            $subject = 'New message with attachment';
            $to = get_option('admin_email');
            $sent = wp_mail($to, $subject, $message_body, $headers);

            if ($sent) {
                $response['success'] = true;
                $response['message'] = 'Email sent successfully.';
            } else {
                $response['message'] = 'Failed to send email.';
            }

            // Delete file
            unlink($file_path);
        }
    }

    // Return response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
add_action('wp_ajax_send_email_with_attachment', 'send_email_with_attachment_ajax_handler');
add_action('wp_ajax_nopriv_send_email_with_attachment', 'send_email_with_attachment_ajax_handler');