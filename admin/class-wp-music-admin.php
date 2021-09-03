<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://profiles.wordpress.org/abditsori/
 * @since      1.0.0
 *
 * @package    Wp_Music
 * @subpackage Wp_Music/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Music
 * @subpackage Wp_Music/admin
 * @author     Abdi Tolessa <tolesaabdi1@gmail.com>
 */
class Wp_Music_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Music_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Music_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-music-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Music_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Music_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-music-admin.js', array( 'jquery' ), $this->version, false );

	}

	
	/**
	 * Creates a new custom post type, music
	 *
	 * @since 	1.0.0
	 * @access 	public
	 * @uses 	register_post_type()
	 */
	public function create_cpt_music() {

		$args = array( 
			'labels'      => array( 
				'name'          => __( 'Music', 'textdomain' ),
				'singular_name' => __( 'Music', 'textdomain' ),
				),
			'public'             => true,
			'publicly_queryable' => true,
			'has_archive'        => true,
			);

		 register_post_type('music', $args);

	}

	public function create_taxonomy_genre(){

		$labels = array(
			'name'              => __( 'Genres', 'textdomain' ),
			'singular_name'     => __( 'Genre', 'textdomain' ),
			'search_items'      => __( 'Search Genres', 'textdomain' ),
			'all_items'         => __( 'All Genres', 'textdomain' ),
			'parent_item'       => __( 'Parent Genre', 'textdomain' ),
			'parent_item_colon' => __( 'Parent Genre:', 'textdomain' ),
			'edit_item'         => __( 'Edit Genre', 'textdomain' ),
			'update_item'       => __( 'Update Genre', 'textdomain' ),
			'add_new_item'      => __( 'Add New Genre', 'textdomain' ),
			'new_item_name'     => __( 'New Genre Name', 'textdomain' ),
			'menu_name'         => __( 'Genre', 'textdomain' ),
		);
	 
		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'genre' ),
		);

		register_taxonomy('genre', 'music', $args);
	}

	public function create_taxonomy_music_tag(){
		$labels = array(
			'name'                       => __( 'Music Tags', 'textdomain' ),
			'singular_name'              => __( 'Music Tag', 'textdomain' ),
			'search_items'               => __( 'Search Music Tags', 'textdomain' ),
			'popular_items'              => __( 'Popular Music Tags', 'textdomain' ),
			'all_items'                  => __( 'All Music Tags', 'textdomain' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit music tag', 'textdomain' ),
			'update_item'                => __( 'Update music tag', 'textdomain' ),
			'add_new_item'               => __( 'Add New music tag', 'textdomain' ),
			'new_item_name'              => __( 'New music tag Name', 'textdomain' ),
			'separate_items_with_commas' => __( 'Separate music tags with commas', 'textdomain' ),
			'add_or_remove_items'        => __( 'Add or remove music tags', 'textdomain' ),
			'choose_from_most_used'      => __( 'Choose from the most used music tags', 'textdomain' ),
			'not_found'                  => __( 'No music tags found.', 'textdomain' ),
			'menu_name'                  => __( 'Music Tags', 'textdomain' ),
		);
	 
		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'music-tag' ),
		);
	 
		register_taxonomy( 'music-tag', 'music', $args );
	}

}
