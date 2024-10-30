<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Immdg
 * @subpackage Immdg/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Immdg
 * @subpackage Immdg/admin
 * @author     Bluegamediversion <bluegamediversion@gmail.com>
 */
class Immdg_Admin {

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
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

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
		 * defined in Immdg_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Immdg_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/immdg-admin.css', array(), $this->version, 'all' );

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
		 * defined in Immdg_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Immdg_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$my_current_screen = get_current_screen();
		if ( is_admin() ) {
			if ( 'immersive_config' === $my_current_screen->id ) {
				wp_enqueue_script( 'immdg-three', IMMDG_PLUGIN_PATH . 'public/js/threejs/three.js', array( 'jquery' ), $this->version, false );
				wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/immdg-admin.js', array( 'jquery' ), $this->version, false );
				wp_enqueue_script( 'immdg-admin-model-control', plugin_dir_url( __FILE__ ) . 'js/immdg-model-control.js', array( 'jquery' ), $this->version, false );
				add_filter( 'script_loader_tag', array( $this, 'add_type_attribute' ), 10, 3 );
			}
		}

		$data = array(
			'iframe_url' => esc_url( get_upload_iframe_src() ),
		);
		wp_localize_script( $this->plugin_name, 'idg_vars', $data );

	}

	public function add_type_attribute( $tag, $handle, $src ) {
		// if not your script, do nothing and return original $tag.
		if ( 'immdg-admin-model-control' !== $handle ) {
			return $tag;
		}
		// change the script tag by adding type="module" and return it.
		$tag = wp_get_script_tag(
			array(
				'src'  => esc_url( $src ),
				'type' => 'module',
			)
		);
		return $tag;
	}

	/**
	 * Register the configuration custom post type
	 */
	public function register_immersive_designer_configuration_cpt() {
		$labels = array(
			'name'                  => _x( 'Immersive Designer', 'Post Type General Name', 'immersive-designer' ),
			'singular_name'         => _x( 'Immersive Designer', 'Post Type Singular Name', 'immersive-designer' ),
			'menu_name'             => __( 'Immersive Designer', 'immersive-designer' ),
			'name_admin_bar'        => __( 'Immersive Designer', 'immersive-designer' ),
			'archives'              => __( 'Our', 'immersive-designer' ),
			'attributes'            => __( 'Attributes', 'immersive-designer' ),
			'parent_item_colon'     => __( 'Parent :', 'immersive-designer' ),
			'all_items'             => __( 'All Configurations', 'immersive-designer' ),
			'add_new_item'          => __( 'Add New', 'immersive-designer' ),
			'add_new'               => __( 'Add New', 'immersive-designer' ),
			'new_item'              => __( 'New', 'immersive-designer' ),
			'edit_item'             => __( 'Edit', 'immersive-designer' ),
			'update_item'           => __( 'Update', 'immersive-designer' ),
			'view_item'             => __( 'View ', 'immersive-designer' ),
			'view_items'            => __( 'View', 'immersive-designer' ),
			'search_items'          => __( 'Search', 'immersive-designer' ),
			'not_found'             => __( 'Not found', 'immersive-designer' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'immersive-designer' ),
			'featured_image'        => __( ' Sketch', 'immersive-designer' ),
			'set_featured_image'    => __( 'Set sketch', 'immersive-designer' ),
			'remove_featured_image' => __( 'Remove sketch', 'immersive-designer' ),
			'use_featured_image'    => __( 'Use as sketch', 'immersive-designer' ),
			'insert_into_item'      => __( 'Insert into sheet', 'immersive-designer' ),
			'uploaded_to_this_item' => __( 'Uploaded to this sheet', 'immersive-designer' ),
			'items_list'            => __( 'list', 'immersive-designer' ),
			'items_list_navigation' => __( 'list navigation', 'immersive-designer' ),
			'filter_items_list'     => __( 'Filter  list', 'immersive-designer' ),
		);

		$args = array(
			'label'                 => __( 'Immersive Designer', 'immersive-designer' ),
			'description'           => __( 'Create 3d product', 'immersive-designer' ),
			'labels'                => $labels,
			'supports'              => array( 'title' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-hammer',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'post',
			'show_in_rest'          => true,
			'rest_base'             => 'idg-api',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
		);
		register_post_type( IMMDG_CONFIG_CPT, $args );
	}

	/**
	 * Add meta box to configuration ctp with options page.
	 *
	 * @return void
	 */
	public function get_main_options_metabox() {
		$screen = array( IMMDG_CONFIG_CPT );
		add_meta_box(
			'immdg-options-box',
			__( 'Main File configuration Options', 'immersive-designer' ),
			array( $this, 'get_idg_main_options_page' ),
			$screen
		);
		add_meta_box(
			'immdg-options-part-config',
			__( 'Part Configuration', 'immersive-designer' ),
			array( $this, 'get_idg_part_config_page' ),
			$screen
		);
	}

	/**
	 * Get idg option page that will be added to metabox.
	 *
	 * @return void
	 */
	public function get_idg_main_options_page() {
		$post_id                 = get_the_ID();
		$current_meta_idg_option = get_post_meta( $post_id, IMMDG_OPTIONS, true );
		$idg_main_config_name    = isset( $current_meta_idg_option['idg_main_config'] ) ? $current_meta_idg_option['idg_main_config'] : array();
		wp_enqueue_media();
		?>

		<form method="POST" >
			<table class="form-table">
				<tr>
					<th> </th>
					<th><?php esc_html_e( 'Config Title', 'immersive-designer' ); ?></th>
					<th>
						<strong>
							<?php esc_html_e( 'Choose Config Part', 'immersive-designer' ); ?>
						</strong>
					</th>
					<th><?php esc_html_e( 'Icon', 'immersive-designer' ); ?></th>
					<th><?php esc_html_e( 'Price', 'immersive-designer' ); ?></th>
				</tr>
				<tr data-lastkey="title_icon">
					<td><label><?php esc_html_e( 'Config Settings', 'immersive-designer' ); ?></label></td>
					<?php
						$val = '';
					if ( isset( $current_meta_idg_option['idg_main_config_settings_title'] ) ) {
						$val = $current_meta_idg_option['idg_main_config_settings_title'];
					}
					?>
					<td><input name="<?php echo esc_attr( IMMDG_OPTIONS ); ?>[idg_main_config_settings_title]" value="<?php echo esc_attr( $val ); ?>"/></td>

					<td>
						<select class ="idg-select-part-config" name="<?php echo esc_attr( IMMDG_OPTIONS ); ?>[idg_main_config_settings_part_config]">
							<option>select main part</option>
							<?php
							if ( isset( $current_meta_idg_option['idg_main_config_settings_part_config'] ) ) {
								echo '<option  selected >' . wp_kses_post( $current_meta_idg_option['idg_main_config_settings_part_config'] ) . '</option>';
							}
							?>
						</select>
					</td>

					<td>
						<?php
						$upload_link  = esc_url( get_upload_iframe_src() );
						$config_value = isset( $current_meta_idg_option['idg_main_config_settings_icon'] ) ? $current_meta_idg_option['idg_main_config_settings_icon'] : null;

						// Get the image src
						$your_img_src = wp_get_attachment_image_src( $config_value, 'full' );

						// For convenience, see if the array is valid
						$you_have_img = is_array( $your_img_src );
						$config_icon  = 'title_icon';
						?>
							<div class="image-upload">
								<div id="show-icon-container<?php echo esc_attr( $config_icon ); ?>">
									<?php if ( $you_have_img ) : ?>
										<img src="<?php echo esc_attr( $your_img_src[0] ); ?>" alt="" id="show-icon<?php echo esc_attr( $config_icon ); ?>"/>
									<?php endif; ?>
								</div>
								<input id="icon-input<?php echo esc_attr( $config_icon ); ?>" type="hidden" name="<?php echo esc_attr( IMMDG_OPTIONS ); ?>[idg_main_config_settings_icon]" value="<?php echo esc_attr( $config_value ); ?>"/>
								<p class="hide-if-no-js">
									<a class="upload-custom-icon<?php echo esc_attr( $config_icon ); ?> <?php
									if ( $you_have_img ) {
										echo 'hidden'; }
									?>
									"  href="<?php echo esc_url( $upload_link ); ?>"><?php esc_html_e( 'Set custom image', 'immersive-designer' ); ?></a>
									<a class="delete-custom-icon<?php echo esc_attr( $config_icon ); ?> <?php
									if ( ! $you_have_img ) {
										echo 'hidden'; }
									?>
									" href="#"><?php esc_html_e( 'Remove this image', 'immersive-designer' ); ?></a>
								</p>
							</div>
					</td>
					<?php
						$val = '';
					if ( isset( $current_meta_idg_option['idg_main_config_settings_price'] ) ) {
						$val = $current_meta_idg_option['idg_main_config_settings_price'];
					}
					?>
					<td>
						<input type="number" step="0.1" name="<?php echo esc_attr( IMMDG_OPTIONS ); ?>[idg_main_config_settings_price]" value="<?php echo esc_attr( $val ); ?>"/>
					</td>
				</tr>
			</table>
			<table class="form-table" id="idg-form-table">
				<thead>
					<tr>
						<th>
							<strong>
								<?php esc_html_e( 'Name', 'immersive-designer' ); ?>
							</strong>
						</th>
						<th>
							<strong>
								<?php esc_html_e( 'Type', 'immersive-designer' ); ?>
							</strong>
						</th>
						<th>
							<strong>
								<?php esc_html_e( 'File', 'immersive-designer' ); ?>
							</strong>
						</th>
						<th>
							<strong>
								<?php esc_html_e( 'Icon', 'immersive-designer' ); ?>
							</strong>
						</th>
						<th>
							<strong>
								<?php esc_html_e( 'Actions', 'immersive-designer' ); ?>
							</strong>
						</th>
						<th>
							<strong>
								<?php esc_html_e( 'Price', 'immersive-designer' ); ?>
							</strong>
						</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$idg_main_config_name = is_array( $idg_main_config_name ) ? $idg_main_config_name : array();

				foreach ( $idg_main_config_name as $key => $id_main_configs ) {
					?>
						<tr data-lastkey="<?php echo esc_attr( $key ); ?>">
							<?php
							if ( is_array( $id_main_configs ) ) {
									$type         = 'text';
									$config_value = $id_main_configs;
								if ( isset( $id_main_configs ['idg_main_config_name'] ) ) {
									$config_key   = 'idg_main_config_name';
									$config_value = $id_main_configs ['idg_main_config_name'];
									?>
											<td>
												<input type="<?php echo esc_attr( $type ); ?>" name="<?php echo esc_attr( IMMDG_OPTIONS ); ?>[idg_main_config][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $config_key ); ?>]" value="<?php echo esc_attr( $config_value ); ?>" />
											</td>
										<?php
								}
								if ( isset( $id_main_configs ['idg_main_config_type'] ) ) {
									$config_key   = 'idg_main_config_type';
									$config_value = $id_main_configs ['idg_main_config_type'];
									?>
											<td>
												<select id="idg_main_config_type<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( IMMDG_OPTIONS ); ?>[idg_main_config][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $config_key ); ?>]" >
													<option value="Color" selected>Color</option>
													<option value="Material" disabled>Material(not available)</option>
													<option value="Texture" disabled>Texture (not available)</option>
												</select>
											</td>
										<?php
								}
								if ( isset( $id_main_configs ['idg_main_config_type_assoc'] ) ) {
									$config_key = 'idg_main_config_type_assoc';
									if ( isset( $id_main_configs ['idg_main_config_type'] ) && 'color' === strtolower( $id_main_configs ['idg_main_config_type'] ) ) {
										$type         = 'color';
										$config_value = $id_main_configs ['idg_main_config_type_assoc'];
										?>
												<td>
													<div id="idg_config_type_container_<?php echo esc_attr( $key ); ?>">
														<input type="<?php echo esc_attr( $type ); ?>" name="<?php echo esc_attr( IMMDG_OPTIONS ); ?>[idg_main_config][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $config_key ); ?>]" value="<?php echo esc_attr( $config_value ); ?>" />
													</div>
												</td>
											<?php
									} else {
										$config_value = $id_main_configs [ $config_key ];
										$upload_link  = esc_url( get_upload_iframe_src() );

										// Get the image src
										$file_assoc_url  = wp_get_attachment_url( $config_value );
										$file_assoc_name = wp_basename( $file_assoc_url );
										?>
											<td>
												<div id="idg_config_type_container_<?php echo esc_attr( $key ); ?>">
													<div id="type-assoc-container<?php echo esc_attr( $key ); ?>">
													<?php if ( $file_assoc_url ) : ?>
															<?php echo esc_attr( $file_assoc_name ); ?>
														<?php endif; ?>
													</div>
													<input id="type-assoc-input<?php echo esc_attr( $key ); ?>" type="hidden" name="<?php echo esc_attr( IMMDG_OPTIONS ); ?>[idg_main_config][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $config_key ); ?>]" value="<?php echo esc_attr( $config_value ); ?>"/>
													<p class="hide-if-no-js">
														<a class="upload-type-assoc-file<?php echo esc_attr( $key ); ?> <?php
														if ( $file_assoc_url ) {
															echo 'hidden'; }
														?>
														" href="<?php echo esc_url( $upload_link ); ?>"><?php esc_html_e( 'Set file', 'immersive-designer' ); ?></a>
														<a class="delete-type-assoc-file<?php echo esc_attr( $key ); ?> <?php
														if ( ! $file_assoc_url ) {
															echo 'hidden'; }
														?>
														" href="#"><?php esc_html_e( 'Remove file', 'immersive-designer' ); ?></a>
													</p>
												</div>
											</td>
										<?php
									}
								}
								if ( isset( $id_main_configs ['idg_main_config_icon'] ) ) {
									$config_key   = 'idg_main_config_icon';
									$config_value = $id_main_configs ['idg_main_config_icon'];
									$upload_link  = esc_url( get_upload_iframe_src() );

									// Get the image src
									$your_img_src = wp_get_attachment_image_src( $config_value, 'full' );

									// For convenience, see if the array is valid
									$you_have_img = is_array( $your_img_src );
									?>
											<td>
												<div class="image-upload">
													<div id="show-icon-container<?php echo esc_attr( $key ); ?>">
													<?php if ( $you_have_img ) : ?>
															<img src="<?php echo esc_attr( $your_img_src[0] ); ?>" alt="" id="show-icon<?php echo esc_attr( $key ); ?>" class="show_icon"/>
														<?php endif; ?>
													</div>
													<input id="icon-input<?php echo esc_attr( $key ); ?>" type="hidden" name="<?php echo esc_attr( IMMDG_OPTIONS ); ?>[idg_main_config][<?php echo esc_attr( $key ); ?>][idg_main_config_icon]" value="<?php echo esc_attr( $config_value ); ?>"/>
													<p class="hide-if-no-js">
														<a class="upload-custom-icon<?php echo esc_attr( $key ); ?> <?php
														if ( $you_have_img ) {
															echo 'hidden'; }
														?>
														"  href="<?php echo esc_url( $upload_link ); ?>"><?php esc_html_e( 'Set custom image', 'immersive-designer' ); ?></a>
														<a class="delete-custom-icon<?php echo esc_attr( $key ); ?> <?php
														if ( ! $you_have_img ) {
															echo 'hidden'; }
														?>
														" href="#"><?php esc_html_e( 'Remove this image', 'immersive-designer' ); ?></a>
													</p>
												</div>
											</td>
										<?php
								}
							}
							?>
								<td class="wms_rm_tr">
									x<br />
								</td>
								<?php
									$config_key   = 'idg_main_config_price';
									$config_value = isset( $id_main_configs ['idg_main_config_price'] ) ? $id_main_configs ['idg_main_config_price'] : '';
								?>
									<td>
										<input type="number" step="0.1" name="<?php echo esc_attr( IMMDG_OPTIONS ); ?>[idg_main_config][<?php echo esc_attr( $key ); ?>][idg_main_config_price]" value="<?php echo esc_attr( $config_value ); ?>"/>
									</td>
						</tr>
					<?php
				}
				?>
				</tbody>
			</table>
			<button type="button" id="idg-add-tr-form"><?php esc_html_e( 'Add New', 'immersive-designer' ); ?></button>
		</form>
		<input type="hidden" name="securite_nonce" value="<?php echo esc_html( wp_create_nonce( 'securite-nonce-idg' ) ); ?>"/>
		<?php
	}

	/**
	 * Get idg option page that will be added to metabox.
	 *
	 * @return void
	 */
	public function get_idg_part_config_page() {
		$post_id                 = get_the_ID();
		$current_meta_idg_option = get_post_meta( $post_id, IMMDG_PART_CONFIG_OPTION, true );
		$idg_part_config_name    = isset( $current_meta_idg_option['idg_part_config'] ) ? $current_meta_idg_option['idg_part_config'] : array();
		wp_enqueue_media();
		?>
			<table class="form-table">
				<tr>
					<th> </th>
					<th><?php esc_html_e( 'Config Title', 'immersive-designer' ); ?></th>
					<th><?php esc_html_e( 'Icon', 'immersive-designer' ); ?></th>
					<th><?php esc_html_e( 'Price', 'immersive-designer' ); ?></th>
				</tr>
				<tr data-lastkey="title_icon">
					<td><label><?php esc_html_e( 'Config Settings', 'immersive-designer' ); ?></label></td>
					<?php
						$val = '';
					if ( isset( $current_meta_idg_option['idg_part_config_settings_title'] ) ) {
						$val = $current_meta_idg_option['idg_part_config_settings_title'];
					}
					?>
					<td><input name="<?php echo esc_attr( IMMDG_PART_CONFIG_OPTION ); ?>[idg_part_config_settings_title]" value="<?php echo esc_attr( $val ); ?>"/></td>
					<td>
						<?php
						$upload_link  = esc_url( get_upload_iframe_src() );
						$config_value = isset( $current_meta_idg_option['idg_part_config_settings_icon'] ) ? $current_meta_idg_option['idg_part_config_settings_icon'] : null;

						// Get the image src
						$your_img_src = wp_get_attachment_image_src( $config_value, 'full' );

						// For convenience, see if the array is valid
						$you_have_img = is_array( $your_img_src );
						$config_icon  = 'title_icon';
						?>
							<div class="image-upload">
								<div id="show-config-part-icon-container<?php echo esc_attr( $config_icon ); ?>">
									<?php if ( $you_have_img ) : ?>
										<img src="<?php echo esc_url( $your_img_src[0] ); ?>" alt="" id="show-icon<?php echo esc_attr( $config_icon ); ?>"/>
									<?php endif; ?>
								</div>
								<input id="icon-config-part-input<?php echo esc_attr( $config_icon ); ?>" type="hidden" name="<?php echo esc_attr( IMMDG_PART_CONFIG_OPTION ); ?>[idg_part_config_settings_icon]" value="<?php echo esc_attr( $config_value ); ?>"/>
								<p class="hide-if-no-js">
									<a class="upload-custom-config-part-icon<?php echo esc_attr( $config_icon ); ?> <?php
									if ( $you_have_img ) {
										echo 'hidden'; }
									?>
									"  href="<?php echo esc_url( $upload_link ); ?>"><?php esc_html_e( 'Set custom image', 'immersive-designer' ); ?></a>
									<a class="delete-custom-config-part-icon<?php echo esc_attr( $config_icon ); ?> <?php
									if ( ! $you_have_img ) {
										echo 'hidden'; }
									?>
									" href="#"><?php esc_html_e( 'Remove this image', 'immersive-designer' ); ?></a>
								</p>
							</div>
					</td>
					<?php
						$val = '';
					if ( isset( $current_meta_idg_option['idg_part_config_settings_price'] ) ) {
						$val = $current_meta_idg_option['idg_part_config_settings_price'];
					}
					?>
					<td>
						<input type="number" step="0.1" name="<?php echo esc_attr( IMMDG_PART_CONFIG_OPTION ); ?>[idg_part_config_settings_price]" value="<?php echo esc_attr( $val ); ?>"/>
					</td>
				</tr>
			</table>
			<table class="form-table" id="idg-part-config-table">
				<thead>
					<tr>
						<th>
							<strong>
								<?php esc_html_e( 'Name', 'immersive-designer' ); ?>
							</strong>
						</th>
						<th>
							<strong>
								<?php esc_html_e( 'Part Config', 'immersive-designer' ); ?>
							</strong>
						</th>
						<th>
							<strong>
								<?php esc_html_e( 'Type', 'immersive-designer' ); ?>
							</strong>
						</th>
						<th>
							<strong>
								<?php esc_html_e( 'File', 'immersive-designer' ); ?>
							</strong>
						</th>
						<th>
							<strong>
								<?php esc_html_e( 'Icon', 'immersive-designer' ); ?>
							</strong>
						</th>
						<th>
							<strong>
								<?php esc_html_e( 'Actions', 'immersive-designer' ); ?>
							</strong>
						</th>
						<th>
							<strong>
								<?php esc_html_e( 'Price', 'immersive-designer' ); ?>
							</strong>
						</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$idg_part_config_name = is_array( $idg_part_config_name ) ? $idg_part_config_name : array();

				foreach ( $idg_part_config_name as $key => $id_main_configs ) {
					?>
						<tr data-lastkey="<?php echo esc_attr( $key ); ?>">
							<?php
							if ( is_array( $id_main_configs ) ) {
									$type         = 'text';
									$config_value = $id_main_configs;
								if ( isset( $id_main_configs ['idg_part_config_name'] ) ) {
									$config_key   = 'idg_part_config_name';
									$config_value = $id_main_configs ['idg_part_config_name'];
									?>
											<td>
												<input type="<?php echo esc_attr( $type ); ?>" name="<?php echo esc_attr( IMMDG_PART_CONFIG_OPTION ); ?>[idg_part_config][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $config_key ); ?>]" value="<?php echo esc_attr( $config_value ); ?>" />
											</td>
										<?php
								}
								if ( isset( $id_main_configs ['idg_part_config_choice'] ) ) {
									$config_key   = 'idg_part_config_choice';
									$config_value = $id_main_configs [ $config_key ];
									?>
										<td>
											<select class ="idg-select-part-config" name="<?php echo esc_attr( IMMDG_PART_CONFIG_OPTION ); ?>[idg_part_config][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $config_key ); ?>]" >
												<option disable>select option</option>
												<?php
												if ( isset( $config_value ) ) {
													echo '<option  selected >' . wp_kses_post( $config_value ) . '</option>';
												}
												?>
											</select>
										</td>
									<?php
								}
								if ( isset( $id_main_configs ['idg_part_config_type'] ) ) {
									$config_key   = 'idg_part_config_type';
									$config_value = $id_main_configs ['idg_part_config_type'];
									?>
											<td>
												<select id="idg_part_config_type<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( IMMDG_PART_CONFIG_OPTION ); ?>[idg_part_config][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $config_key ); ?>]" >
													<option value="Color" selected>Color</option>
													<option value="Material" disabled>Material(not available)</option>
													<option value="Texture" disabled>Texture (not available)</option>
												</select>
											</td>
										<?php
								}
								if ( isset( $id_main_configs ['idg_part_config_type_assoc'] ) ) {
									$config_key = 'idg_part_config_type_assoc';
									if ( isset( $id_main_configs ['idg_part_config_type'] ) && 'color' === strtolower( $id_main_configs ['idg_part_config_type'] ) ) {
										$type         = 'color';
										$config_value = $id_main_configs ['idg_part_config_type_assoc'];
										?>
												<td>
													<div id="idg_part_config_container_<?php echo esc_attr( $key ); ?>">
														<input type="<?php echo esc_attr( $type ); ?>" name="<?php echo esc_attr( IMMDG_PART_CONFIG_OPTION ); ?>[idg_part_config][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $config_key ); ?>]" value="<?php echo esc_attr( $config_value ); ?>" />
													</div>
												</td>
											<?php
									} else {
										$config_value = $id_main_configs [ $config_key ];
										$upload_link  = esc_url( get_upload_iframe_src() );

										// Get the image src
										$file_assoc_url  = wp_get_attachment_url( $config_value );
										$file_assoc_name = wp_basename( $file_assoc_url );
										?>
											<td>
												<div id="idg_part_config_container_<?php echo esc_attr( $key ); ?>">
													<div id="type-config-part-assoc-container<?php echo esc_attr( $key ); ?>">
													<?php if ( $file_assoc_url ) : ?>
															<?php echo esc_attr( $file_assoc_name ); ?>
														<?php endif; ?>
													</div>
													<input id="type-assoc-input<?php echo esc_attr( $key ); ?>" type="hidden" name="<?php echo esc_attr( IMMDG_PART_CONFIG_OPTION ); ?>[idg_part_config][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $config_key ); ?>]" value="<?php echo esc_attr( $config_value ); ?>"/>
													<p class="hide-if-no-js">
														<a class="upload-custom-config-part-assoc-file<?php echo esc_attr( $key ); ?> <?php
														if ( $file_assoc_url ) {
															echo 'hidden'; }
														?>
														" href="<?php echo esc_url( $upload_link ); ?>"><?php esc_html_e( 'Set file', 'immersive-designer' ); ?></a>
														<a class="delete-custom-config-part-assoc-file<?php echo esc_attr( $key ); ?> <?php
														if ( ! $file_assoc_url ) {
															echo 'hidden'; }
														?>
														" href="#"><?php esc_html_e( 'Remove file', 'immersive-designer' ); ?></a>
													</p>
												</div>
											</td>
										<?php
									}
								}
								if ( isset( $id_main_configs ['idg_part_config_icon'] ) ) {
									$config_key   = 'idg_part_config_icon';
									$config_value = $id_main_configs ['idg_part_config_icon'];
									$upload_link  = esc_url( get_upload_iframe_src() );

									// Get the image src
									$your_img_src = wp_get_attachment_image_src( $config_value, 'full' );

									// For convenience, see if the array is valid
									$you_have_img = is_array( $your_img_src );
									?>
											<td>
												<div class="image-upload">
													<div id="show-config-part-icon-container<?php echo esc_attr( $key ); ?>">
													<?php if ( $you_have_img ) : ?>
															<img src="<?php echo esc_url( $your_img_src[0] ); ?>" alt="" id="show-icon<?php echo esc_attr( $key ); ?>" class="show_icon"/>
														<?php endif; ?>
													</div>
													<input id="icon-config-part-input<?php echo esc_attr( $key ); ?>" type="hidden" name="<?php echo esc_attr( IMMDG_PART_CONFIG_OPTION ); ?>[idg_part_config][<?php echo esc_attr( $key ); ?>][idg_part_config_icon]" value="<?php echo esc_attr( $config_value ); ?>"/>
													<p class="hide-if-no-js">
														<a class="upload-custom-config-part-icon<?php echo esc_attr( $key ); ?> <?php
														if ( $you_have_img ) {
															echo 'hidden';
														}
														?>
														"  href="<?php echo esc_url( $upload_link ); ?>"><?php esc_html_e( 'Set custom image', 'immersive-designer' ); ?></a>
														<a class="delete-custom-config-part-icon<?php echo esc_attr( $key ); ?> <?php
														if ( ! $you_have_img ) {
															echo 'hidden';
														}
														?>
														" href="#"><?php esc_html_e( 'Remove this image', 'immersive-designer' ); ?></a>
													</p>
												</div>
											</td>
										<?php
								}
							}
							?>
								<td class="wms_part_rm_tr">
									x<br />
								</td>
								<?php
									$config_key   = 'idg_part_config_opt_price';
									$config_value = isset( $id_main_configs ['idg_part_config_opt_price'] ) ? $id_main_configs ['idg_part_config_opt_price'] : '';
								?>
									<td>
										<input type="number" step="0.1"  name="<?php echo esc_attr( IMMDG_PART_CONFIG_OPTION ); ?>[idg_part_config][<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $config_key ); ?>]" value="<?php echo esc_attr( $config_value ); ?>"/>
									</td>
						</tr>
					<?php
				}
				?>
				</tbody>
			</table>
			<button type="button" id="idg-add-tr-part-config-form"><?php esc_html_e( 'Add New', 'immersive-designer' ); ?></button>
		<input type="hidden" name="securite_nonce" value="<?php echo esc_html( wp_create_nonce( 'securite-nonce-idg' ) ); ?>"/>
		<?php
	}

	/**
	 * Add meta box to configuration ctp with options page.
	 *
	 * @return void
	 */
	public function save_immersive_config_cpt( $post_id, $post, $update ) {
		if ( isset( $_POST['securite_nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['securite_nonce'] ), 'securite-nonce-idg' ) ) {
			$to_save             = isset( $_POST[ IMMDG_OPTIONS ] ) ? immdg_sanitize_text_field_array_of_array( wp_unslash( $_POST[ IMMDG_OPTIONS ] ) ) : array();
			$to_save_config_part = isset( $_POST[ IMMDG_PART_CONFIG_OPTION ] ) ? immdg_sanitize_text_field_array_of_array( wp_unslash( $_POST[ IMMDG_PART_CONFIG_OPTION ] ) ) : array();
			$model_config_loader = isset( $_POST['immdg'] ) ? immdg_sanitize_text_field_array_of_array( wp_unslash( $_POST['immdg'] ) ) : array();
			// Any of the WordPress data sanitization functions can be used here
			// $tags = array_map( 'esc_attr', $tags );
			update_post_meta( $post_id, IMMDG_OPTIONS, $to_save );
			update_post_meta( $post_id, IMMDG_PART_CONFIG_OPTION, $to_save_config_part );
			update_post_meta( $post_id, 'immdg', $model_config_loader );
		}
	}

	/**
	 *
	 */
	function add_extension_types( $mime_types ) {
		$mime_types['obj'] = 'text/plain'; // Adding obj files.
		$mime_types['mtl'] = 'text/plain'; // Adding mtl files.
		return $mime_types;
	}

	public function get_product_tab_label( $tabs ) {
		if ( ! is_array( $tabs ) ) {
			return $tabs;
		}
		$tabs['immdg_tab'] = array(
			'label'  => __( 'Immersive Designer', 'immersive-designer' ),
			'target' => 'immdg_tab_content',
			'class'  => array(),
		);
		return $tabs;
	}

	public function get_product_tab_data() {
		?>
		<div id='immdg_tab_content' class='panel woocommerce_options_panel'>
			<div class='options_group'>
				<?php
				$args               = array(
					'post_type' => 'immersive_config',
				);
				$get_configurations = get_posts( $args );
				$list               = array();
				$list['']           = '';
				foreach ( $get_configurations as  $post ) {
					$list[ $post->ID ] = $post->post_title;
				}

				woocommerce_wp_select(
					array(
						'id'      => IMMDG_SELECTOR,
						'label'   => __( 'Immersive Designer Configuration', 'immersive-designer' ),
						'options' => $list,
					)
				);
				woocommerce_wp_text_input(
					array(
						'id'    => 'immdg_nonce',
						'label' => ' ',
						'value' => wp_create_nonce( 'immdg_securite' ),
						'type'  => 'hidden',
					)
				);
				?>
		</div>
	</div>
		<?php
	}

	public function save_product_config( $post_id ) {
		if ( ( isset( $_POST[ IMMDG_SELECTOR ], $_POST['immdg_nonce'] ) &&
		wp_verify_nonce( sanitize_key( $_POST['immdg_nonce'] ), 'immdg_securite' ) ) ) {
			$val = sanitize_text_field( wp_unslash( $_POST[ IMMDG_SELECTOR ] ) );
			update_post_meta( $post_id, IMMDG_SELECTOR, $val );
		}
	}

	public function add_setting_page_to_menu() {
		$parent_slug = 'edit.php?post_type=immersive_config';
		add_submenu_page( $parent_slug, __( 'Settings', 'immersive-designer' ), __( 'Settings', 'immersive-designer' ), 'manage_options', 'immdg', array( $this, 'configure_settings_page' ) );
	}

	public function configure_settings_page() {
		if ( ( isset( $_POST['immdg-settings'], $_POST['securite_nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['securite_nonce'] ), 'immdg_securite' ) ) ) {
			if ( ! isset( $_POST['immdg-settings']['immdg_configurator_page'] ) ) {
				$_POST['immdg-settings']['immdg_configurator_page'] = null;
			}
			$data = array_map( 'sanitize_text_field', wp_unslash( $_POST['immdg-settings'] ) );
			immdg_update_option( $data );

			?>
			<div class="wad notice notice-success is-dismissible">
				<p>
					<?php
						echo wp_kses_post( '<b>' . esc_attr__( IMMDG_PLUGIN_NAME, 'immersive-designer' ) . '</b>' . sprintf( __( ': Data saved successful!', 'immersive-designer' ) ) );
					?>
				</p>
			</div>
			<?php
		}

		$immdg_configurator_page = get_option( 'immdg_configurator_page', null );

		// create default page.
		// $config_page = get_page_by_title( IMMDG_CONFIGURATOR_PAGE_TITLE );
		// if ( ! $config_page ) {
		// $wordpress_page = array(
		// 'post_title'   => IMMDG_CONFIGURATOR_PAGE_TITLE,
		// 'post_content' => '<strong>Configurator Page</strong>',
		// 'post_status'  => 'publish',
		// 'post_author'  => 1,
		// 'post_type'    => 'page',
		// );
		// wp_insert_post( $wordpress_page );
		// }
		$pages = get_pages();
		?>
		<div class="wrap">
			<h1 style="font-size: 23px; text-transform: uppercase; margin: 1em 0;"><?php esc_html_e( 'Immersive Designer Settings', 'immersive-designer' ); ?></h1>
			<form method="POST">
				<table class="form-table">
					<tr valign="top">
						<div class="col-auto my-1">
							<th scope="row">
								<strong>
									<?php esc_attr_e( 'Configurator Page', 'immersive-designer' ); ?>
								</strong>
							</th>
							<td>
								<select name="immdg-settings[immdg_configurator_page]">
									<?php
									foreach ( $pages as $page ) {
										$selected = '';
										if ( ! $immdg_configurator_page ) {
											if ( IMMDG_CONFIGURATOR_PAGE_TITLE === $page->post_title ) {
												$selected = 'selected';
											}
										} elseif ( $page->ID === (int) $immdg_configurator_page ) {
											$selected = 'selected';
										}
										?>
										<option value="<?php echo esc_attr( $page->ID ); ?>" <?php echo esc_attr( $selected ); ?> ><?php echo esc_attr( $page->post_title ); ?></option>
										<?php
									}
									?>
								</select>
							</td>
						</div>
					</tr>
				</table>
				<input type="hidden" name="securite_nonce" value="<?php echo esc_html( wp_create_nonce( 'immdg_securite' ) ); ?>"/>
				<span ><?php submit_button(); ?></span>
			</form>
		</div>
		<?php
	}
}
