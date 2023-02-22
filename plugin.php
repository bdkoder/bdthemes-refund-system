<?php

namespace Bdthemes\RefundSystem;

/**
 * Class Plugin
 *
 * Main Plugin class
 * @since 1.2.0
 */
class Plugin {

	/**
	 * Instance
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * widget_scripts
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function widget_scripts() {
		$suffix                    = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
		wp_register_script('bdt-rs-form', plugins_url('/assets/js/refund-form.js', __FILE__), ['jquery'], BDT_REFUND_SYSTEM_VERSION, true);
		wp_register_script('sweetalert2', plugins_url('/assets/js/sweetalert2.all.min.js', __FILE__), ['jquery'], BDT_REFUND_SYSTEM_VERSION, true);
	}

	/**
	 * Editor scripts
	 *
	 * Enqueue plugin javascripts integrations for Elementor editor.
	 *
	 * @since 1.2.1
	 * @access public
	 */
	public function editor_scripts() {
		add_filter('script_loader_tag', [$this, 'editor_scripts_as_a_module'], 10, 2);
		wp_enqueue_script(
			'elementor-hello-world-editor',
			plugins_url('/assets/js/editor/editor.js', __FILE__),
			[
				'elementor-editor',
			],
			BDT_REFUND_SYSTEM_VERSION,
			true
		);
	}

	public function load_admin_scripts() {
		$suffix                    = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_script('sweetalert2', plugins_url('/assets/js/sweetalert2.all.min.js', __FILE__), ['jquery'], BDT_REFUND_SYSTEM_VERSION, true);
	}

	/**
	 * Force load editor script as a module
	 *
	 * @since 1.2.1
	 *
	 * @param string $tag
	 * @param string $handle
	 *
	 * @return string
	 */
	public function editor_scripts_as_a_module($tag, $handle) {
		if ('elementor-hello-world-editor' === $handle) {
			$tag = str_replace('<script', '<script type="module"', $tag);
		}

		return $tag;
	}

	public function widget_styles() {

		wp_register_style('bdt-rs-form', BDT_REFUND_SYSTEM_ASSETS . 'css/bdt-rs-form.css', [], BDT_REFUND_SYSTEM_VERSION, 'all');
	}

	/**
	 * Include Widgets files
	 *
	 * Load widgets files
	 *
	 * @since 1.2.0
	 * @access private
	 */
	private function include_widgets_files() {
		require_once BDT_REFUND_SYSTEM__PATH . '/widgets/refund-form.php';
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function register_widgets($widgets_manager) {
		$this->include_widgets_files();

		// Register Widgets
		$widgets_manager->register(new \Bdthemes\RefundSystem\Widgets\Refund_Form());
	}

	/**
	 * Add page settings controls
	 *
	 * Register new settings for a document page settings.
	 *
	 * @since 1.2.1
	 * @access private
	 */

	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 1.2.0
	 * @access public
	 */

	public function elementor_init() {
		// Add element category in panel
		\Elementor\Plugin::instance()->elements_manager->add_category(
			'bdt-refund-system', // This is the name of your addon's category and will be used to group your widgets/elements in the Edit sidebar pane!
			[
				'title' => __('Refund System', 'bdt-refund-system'), // The title of your modules category - keep it simple and short!
				'icon'  => 'font',
			],
			1
		);
	}

	public function __construct() {

		add_action('elementor/init', [$this, 'elementor_init']);

		// Register widgets
		add_action('elementor/widgets/register', [$this, 'register_widgets']);

		// Register widget scripts
		add_action('elementor/frontend/after_register_scripts', [$this, 'widget_scripts']);


		// Register editor scripts
		add_action('elementor/editor/after_enqueue_scripts', [$this, 'editor_scripts']);

		// admin js
		add_action('admin_enqueue_scripts', [$this, 'load_admin_scripts']);

		// Register Widget Styles
		add_action('elementor/frontend/after_enqueue_styles', [$this, 'widget_styles']);
	}
}

// Instantiate Plugin Class
Plugin::instance();
