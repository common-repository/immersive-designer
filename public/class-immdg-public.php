<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Immdg
 * @subpackage Immdg/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Immdg
 * @subpackage Immdg/public
 * @author     Bluegamediversion <bluegamediversion@gmail.com>
 */
class Immdg_Public {

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
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/immdg-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/immdg-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'ajaxurl', array( admin_url( 'admin-ajax.php' ) ) );
		$page_id = get_option( 'immdg_configurator_page', null );

		if ( is_page( $page_id ) ) {
			wp_enqueue_script( 'pub-immdg-three', IMMDG_PLUGIN_PATH . '/public/js/threejs/three.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'immdg-pub-model-control', plugin_dir_url( __FILE__ ) . 'js/immdg-public-model-control.js', array( 'jquery' ), $this->version, false );
		}
	}

	/**
	 * Add option type 'module' to script tag.
	 */
	public function add_type_attribute( $tag, $handle, $src ) {
		// if not your script, do nothing and return original $tag
		if ( 'immdg-pub-model-control' !== $handle ) {
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
	 * Use this function to display the configurator.
	 */
	public function immersive_display( $config_id, $product_id ) {

		// Get the main part configuration options
		$product_idg_config = get_idg_main_config_by_id( $config_id );

		// Get the scene default configuration
		$scene_conf           = get_idg_scene_config_by_id( $config_id );
		$main_title           = isset( $product_idg_config['idg_main_config_settings_title'] ) ? $product_idg_config['idg_main_config_settings_title'] : '';
		$main_conf_model_name = isset( $product_idg_config['idg_main_config_settings_part_config'] ) ? $product_idg_config['idg_main_config_settings_part_config'] : '';

		$model_path    = $scene_conf['model-path'];
		$material_path = $scene_conf['material-path'];
		$pos_x         = $scene_conf['model-cam-posx'];
		$pos_y         = $scene_conf['model-cam-posy'];
		$pos_z         = $scene_conf['model-cam-posz'];

		$rot_x = $scene_conf['model-cam-rotx'];
		$rot_y = $scene_conf['model-cam-roty'];
		$rot_z = $scene_conf['model-cam-rotz'];

		$back_color   = $scene_conf['model-backg-color'];
		$cam_rotlock  = isset( $scene_conf['model-lock-cam-rot'] ) ? $scene_conf['model-lock-cam-rot'] : 'no';
		$cam_zoomlock = isset( $scene_conf['model-lock-cam-zoom'] ) ? $scene_conf['model-lock-cam-zoom'] : 'no';

		// Get product datas
		$product            = wc_get_product( $product_id );
		$product_base_price = $product->get_price();
		?>
		<h2> Immersive Configurator !</h2>
		<div class="idg-app">
			<div id="idg-root-preview" cam_zoomlock = "<?php echo esc_attr( $cam_zoomlock ); ?>" cam_rotlock = "<?php echo esc_attr( $cam_rotlock ); ?>" back_color = "<?php echo esc_attr( $back_color ); ?>"  pos_x = "<?php echo esc_attr( $pos_x ); ?>" pos_y = "<?php echo esc_attr( $pos_y ); ?>" pos_z = "<?php echo esc_attr( $pos_z ); ?>" rot_z = "<?php echo esc_attr( $rot_z ); ?>" rot_y = "<?php echo esc_attr( $rot_y ); ?>" rot_x = "<?php echo esc_attr( $rot_x ); ?>" mdl = "<?php echo esc_attr( $model_path ); ?>" mtl = "<?php echo esc_attr( $material_path ); ?>" class="">
				<canvas id= "idgApp" style="width: 100%; height: 100%">
				</canvas>
			</div>

			<div id="idg-root-options" class="idg-">
				<?php
				if ( isset( $product_idg_config['idg_main_config_settings_part_config'] ) && ! empty( $product_idg_config['idg_main_config_settings_part_config'] ) ) {
					$main_part = $product_idg_config['idg_main_config_settings_part_config'];
					?>
					<!-- THE CONFIG MAIN OPTION -->
					<div class="idg-opt">
						<div class="idg-opt-head">
							<div class="idg-opt-head-title">
								<span class="opt-title"> <?php echo esc_attr( $main_title ); ?> </span>
								<span>options</span>
							</div>

							<div id="<?php echo esc_attr( str_replace( ' ', '', $main_part . '-thumb' ) ); ?>" class="idg-opt-head-thumb option-thumb" style="background: rgb(45, 148, 117);"></div>
						</div>
						<div class="idg-opt-toggle">
						</div>

						<div class="idg-opt-list">
						<?php
						if ( isset( $product_idg_config['idg_main_config'] ) && ! empty( $product_idg_config['idg_main_config'] ) ) {
							$main_conf_options = $product_idg_config['idg_main_config'];
							foreach ( $main_conf_options as $conf_opt ) {
								?>
										<div class="idg-opt-option">
											<div class="option-thumb idg-option-thumb" opt-price= "<?php echo esc_attr( $conf_opt['idg_main_config_price'] ); ?>" apply-color = <?php echo esc_attr( $conf_opt['idg_main_config_type_assoc'] ); ?> apply-part = <?php echo esc_attr( $main_part ); ?> style="background: <?php echo esc_attr( $conf_opt['idg_main_config_type_assoc'] ); ?>; height: 40px; width: 40px"></div>
										</div>
									<?php
							}
						}
						?>
						</div>
					</div>
					<?php
				}
				?>

				<!-- THE PART CONFIG OPTIONS -->
				<?php
					// Get parts configurations options
					$part_conf_options = get_idg_part_config_by_id( $config_id );
				if ( ! empty( $part_conf_options ) ) {
					foreach ( $part_conf_options as $part_name => $options_name ) {
						?>
							<div class="idg-opt">
								<div class="idg-opt-head">
									<div class="idg-opt-head-title">
										<span class="opt-title"> <?php echo esc_attr( $part_name ); ?> </span>
										<span>options</span>
									</div>
									<div id="<?php echo esc_attr( str_replace( ' ', '', $part_name . '-thumb' ) ); ?>" class="idg-opt-head-thumb option-thumb" style="background: rgb(45, 148, 117);"></div>
								</div>

								<div class="idg-opt-toggle">
								</div>
								<div class="idg-opt-list">
								<?php
								foreach ( $options_name as $conf_opt ) {
									?>
											<div class="idg-opt-option">
												<div class="option-thumb idg-option-thumb" opt-price= "<?php echo esc_attr( $conf_opt['idg_part_config_opt_price'] ); ?>" apply-color = <?php echo esc_attr( $conf_opt['idg_part_config_type_assoc'] ); ?>  apply-part = <?php echo esc_attr( $part_name ); ?> style="background: <?php echo esc_attr( $conf_opt['idg_part_config_type_assoc'] ); ?>; height: 40px; width: 40px"></div>
											</div>
										<?php
								}
								?>
								</div>

							</div>
							<?php
					}
				}
				?>
			</div>
		</div>
		<div class="idg-footer">
				<div class="footer-price-container">
					<div class="idg-price"><span id="currency_symbol"><?php echo esc_attr( get_woocommerce_currency_symbol( get_woocommerce_currency() ) ); ?></span> <span id="idg-conf-price"><?php echo esc_attr( $product_base_price ); ?> </span></div>
				</div>
				<button class="idg-buy-btn"><span id="idg-base-price" idg-cart-price = "<?php echo $product_base_price; ?>">ADD TO CART</span></button>
		</div>
		<br/>
		<div class="immdg_nadd_to_cart_notif woocommerce-message"> <?php _e( 'Product successfully added to cart.', 'immersive-designer' ); ?> 
		<a href="<?php echo esc_url( wc_get_cart_url() ); ?>"> <?php _e( 'view cart.', 'immersive-designer' ); ?> </a> </div>
		<?php
	}

	public function add_configurator_btn_to_product( $html, $product ) {
		$prod_id = $product->get_id();
		$html   .= '<br />' . $this->get_configurator_btn_to_product( $prod_id );
		return $html;
	}
	public function add_configurator_btn_to_product_page() {
		if ( is_product() ) {
			$prod_id = get_the_id();
			$html    = $this->get_configurator_btn_to_product( $prod_id );
			echo wp_kses_post( $html );
		}
	}

	private function get_configurator_btn_to_product( $prod_id ) {
		$product_meta_add_config_btn = get_post_meta( $prod_id, IMMDG_SELECTOR, true );
		$config_btn                  = '';
		if ( $product_meta_add_config_btn ) {
			$page_id   = get_option( 'immdg_configurator_page', null );
			$page_link = get_permalink( $page_id );

			$query = wp_parse_url( $page_link, PHP_URL_QUERY );

			// Returns a string if the URL has parameters or NULL if not
			if ( $query ) {
				$page_link .= '&immdg_prod_id=' . $prod_id . '&immdg_nonce=' . wp_create_nonce( 'securite-nonce-idg' );
			} else {
				$page_link .= '?immdg_prod_id=' . $prod_id . '&immdg_nonce=' . wp_create_nonce( 'securite-nonce-idg' );
			}

			$config_btn = sprintf(
				'<a href="%s" class="%s" data-product_id="%s">%s</a>',
				esc_url( $page_link ),
				esc_attr( 'button' ),
				$prod_id,
				esc_html( 'DESIGN YOUR OWN' )
			);
		}
		return $config_btn;
	}

	function add_configurator_to_page( $content ) {
		$page_id = get_option( 'immdg_configurator_page', null );

		if ( is_page( $page_id ) ) {
			$pid = null;
			if ( isset( $_GET['immdg_nonce'] ) && wp_verify_nonce( sanitize_key( $_GET['immdg_nonce'] ), 'securite-nonce-idg' ) && isset( $_GET['immdg_prod_id'] ) ) {
				$pid = sanitize_text_field( wp_unslash( $_GET['immdg_prod_id'] ) ); // get product id.
			}

			if ( $pid != null ) {
				$config_id_arr = get_post_meta( $pid, IMMDG_SELECTOR );
				$config_id     = $config_id_arr[0];
				$content       = $this->immersive_display( $config_id, $pid );
			}
		}

		return $content;
	}

	public function add_product_to_cart() {
		$product_id       = filter_input( INPUT_POST, 'product_id' );
		$product_qty      = filter_input( INPUT_POST, 'product_qty' );
		$product_price    = filter_input( INPUT_POST, 'product_price' );
		$options_selected = filter_input( INPUT_POST, 'options_selected', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
		WC()->cart->add_to_cart(
			$product_id,
			$product_qty,
			0,
			array(),
			array(
				'immdg_prod'             => true,
				'immdg_prod_price'       => $product_price,
				'immdg_options_selected' => $options_selected,
			)
		);
		wp_die();
	}

	public function set_configure_product_in_cart( $cart ) {
		// Loop through cart items
		foreach ( $cart->get_cart() as $cart_item ) {

			// Get an instance of the WC_Product object
			if ( isset( $cart_item['immdg_prod'] ) ) {
				$product          = $cart_item['data'];
				$options_selected = $cart_item['immdg_options_selected'];

				$product->set_price( $cart_item['immdg_prod_price'] );
				// Get the product name (Added Woocommerce 3+ compatibility)
				$original_name = method_exists( $product, 'get_name' ) ? $product->get_name() : $product->post->post_title;

				// SET THE NEW NAME
				$new_name = $original_name . '<br> ' . implode( ' ', $options_selected );

				// Set the new name (WooCommerce versions 2.5.x to 3+)
				if ( method_exists( $product, 'set_name' ) ) {
					$product->set_name( $new_name );
				} else {
					$product->post->post_title = $new_name;
				}
			}
		}
	}

}
