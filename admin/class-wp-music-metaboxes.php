<?php
/**
 * The metabox-specific functionality of the plugin.
 *
 * @package 	WP_Music
 * @subpackage 	WP_Music/admin
 */
class WP_Music_Metaboxes {

	/**
	 * The post meta data
	 *
	 * @since 		1.0.0
	 * @access 		private
	 * @var 		string 			$meta    			The post meta data.
	 */
	private $meta;

	/**
	 * The ID of this plugin.
	 *
	 * @since 		1.0.0
	 * @access 		private
	 * @var 		string 			$plugin_name 		The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since 		1.0.0
	 * @access 		private
	 * @var 		string 			$version 			The current version of this plugin.
	 */
	private $version;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 		1.0.0
	 * @param 		string 			$Now_Hiring 		The name of this plugin.
	 * @param 		string 			$version 			The version of this plugin.
	 */

	 private $post_id;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
        
		$this->version = $version;

        $this->set_meta();

		$this->post_id = get_the_ID();
	}

	/**
	 * Registers metaboxes with WordPress
	 *
	 * @since 	1.0.0
	 * @access 	public
	 */
	public function add_metaboxes() {

		add_meta_box(
			'wp-music_composer_name',
            __( 'Composer name', 'text-domain' ),
			array( $this, 'metabox' ),
			'music',
			'normal',
			'default',
            array(
				'file' => 'composer-name'
			)
		);

	} // add_metaboxes()

    	/**
	 * Calls a metabox file specified in the add_meta_box args.
	 *
	 * @since 	1.0.0
	 * @access 	public
	 * @return 	void
	 */
	public function metabox( $post, $params ) {

		if ( ! is_admin() ) { return; }
		if ( 'music' !== $post->post_type ) { return; }

		if ( ! empty( $params['args']['classes'] ) ) {

			$classes = 'repeater ' . $params['args']['classes'];

		}

		include( plugin_dir_path( __FILE__ ) . 'partials/wp-music-admin-metabox-' . $params['args']['file'] . '.php' );

	} // metabox()


    private function sanitizer( $type, $data ) {

		if ( empty( $type ) ) { return; }
		if ( empty( $data ) ) { return; }

		$return 	= '';
		$sanitizer 	= new WP_Music_Sanitize();

		$sanitizer->set_data( $data );
		$sanitizer->set_type( $type );

		$return = $sanitizer->clean();

		unset( $sanitizer );

		return $return;

	} // sanitizer()

    /**
	 * Sets the class variable $options
	 */
	public function set_meta() {

		global $post;

		if ( empty( $post ) ) { return; }
		if ( 'music' != $post->post_type ) { return; }

		//wp_die( '<pre>' . print_r( $post->ID ) . '</pre>' );

		$this->meta = get_post_custom( $post->ID );

	} // set_meta()

    /**
	 * Returns an array of the all the metabox fields and their respective types
	 *
	 * @since 		1.0.0
	 * @access 		public
	 * @return 		array 		Metabox fields and types
	 */
	private function get_metabox_fields() {

		$fields = array();

		// $fields[] = array( 'job-requirements-education', 'textarea' );
		// $fields[] = array( 'job-requirements-experience', 'textarea' );
		// $fields[] = array( 'job-requirements-skills', 'textarea' );
		// $fields[] = array( 'job-additional-info', 'textarea' );
		// $fields[] = array( 'job-responsibilities', 'textarea' );
		$fields[] = array( 'composer-name', 'text' );
		//$fields[] = array( 'file-repeater', 'repeater', array( array( 'label-file', 'text' ), array( 'url-file', 'url' ) ) );

		return $fields;

	} // get_metabox_fields()

    /**
	 * Saves metabox data
	 *
	 * Repeater section works like this:
	 *  	Loops through meta fields
	 *  		Loops through submitted data
	 *  		Sanitizes each field into $clean array
	 *   	Gets max of $clean to use in FOR loop
	 *   	FOR loops through $clean, adding each value to $new_value as an array
	 *
	 * @since 	1.0.0
	 * @access 	public
	 * @param 	int 		$post_id 		The post ID
	 * @param 	object 		$object 		The post object
	 * @return 	void
	 */

	public function validate_meta( $post_id, $object ) {

		//wp_die( '<pre>' . print_r( $_POST ) . '</pre>' );

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return $post_id; }
		if ( ! current_user_can( 'edit_post', $post_id ) ) { return $post_id; }
		if ( 'music' !== $object->post_type ) { return $post_id; }

		// $nonce_check = $this->check_nonces( $_POST );

		// if ( 0 < $nonce_check ) { return $post_id; }

		$metas = $this->get_metabox_fields();

		foreach ( $metas as $meta ) {

			$name = $meta[0];
			$type = $meta[1];

			$new_value = $this->sanitizer( $type, $_POST[$name] );

            global $wpdb;

			$custom_table = $wpdb->prefix . 'wp_music';
			
			$record = $wpdb->get_var("SELECT COUNT(*) FROM  $custom_table where post_id = $post_id");

			if($record == 0){

				$wpdb->insert($custom_table, array( 
						'post_id' => $post_id, 
						'composer-name' => $new_value, 
					), 
            	);

			} else{

				$wpdb->update($custom_table,
						array($name => $new_value),
						array('post_id' => $post_id)
				);

			}
			//update_post_meta( $post_id, $name, $new_value );

		} // foreach

	} // validate_meta()

} // class
