<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Immdg
 * @subpackage Immdg/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Immdg
 * @subpackage Immdg/includes
 * @author     Bluegamediversion <bluegamediversion@gmail.com>
 */
class Immdg {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Immdg_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'IMMDG_VERSION' ) ) {
			$this->version = IMMDG_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'immersive-designer';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Immdg_Loader. Orchestrates the hooks of the plugin.
	 * - Immdg_i18n. Defines internationalization functionality.
	 * - Immdg_Admin. Defines all hooks for the admin area.
	 * - Immdg_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-immdg-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-immdg-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-immdg-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-immdg-public.php';

		$this->loader = new Immdg_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Immdg_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Immdg_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Immdg_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_admin, 'register_immersive_designer_configuration_cpt' );
		$this->loader->add_filter( 'upload_mimes', $plugin_admin, 'add_extension_types', 1, 1 );
		$this->loader->add_filter( 'script_loader_tag', $plugin_admin, 'add_type_attribute', 10, 3 );
		$config_idg = new Immdg_Config();
		$this->loader->add_action( 'add_meta_boxes', $config_idg, 'add_idg_config_box', 1, 1 );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'get_main_options_metabox' );
		$this->loader->add_action( 'save_post_' . IMMDG_CONFIG_CPT, $plugin_admin, 'save_immersive_config_cpt', 10, 3 );

		$this->loader->add_filter( 'woocommerce_product_data_tabs', $plugin_admin, 'get_product_tab_label' );
		$this->loader->add_action( 'woocommerce_product_data_panels', $plugin_admin, 'get_product_tab_data' );
		$this->loader->add_action( 'woocommerce_process_product_meta', $plugin_admin, 'save_product_config' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_setting_page_to_menu' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Immdg_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		// $this->loader->add_action( 'woocommerce_after_single_product_summary', $plugin_public, 'immersive_display' );
		$this->loader->add_filter( 'script_loader_tag', $plugin_public, 'add_type_attribute', 10, 3 );

		$this->loader->add_filter( 'woocommerce_loop_add_to_cart_link', $plugin_public, 'add_configurator_btn_to_product', 10, 2 );
		$this->loader->add_filter( 'the_content', $plugin_public, 'add_configurator_to_page' );

		$this->loader->add_action( 'wp_ajax_immdg_add_product_to_cart', $plugin_public, 'add_product_to_cart' );
		$this->loader->add_action( 'wp_ajax_nopriv_immdg_add_product_to_cart', $plugin_public, 'add_product_to_cart' );

		$this->loader->add_action( 'woocommerce_before_calculate_totals', $plugin_public, 'set_configure_product_in_cart' );
		$this->loader->add_action( 'woocommerce_after_add_to_cart_button', $plugin_public, 'add_configurator_btn_to_product_page' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Immdg_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
