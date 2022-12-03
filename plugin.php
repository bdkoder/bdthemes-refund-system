<?php
namespace NasAcademy;

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
		if ( is_null( self::$_instance ) ) {
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
		$suffix                    = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_register_script( 'nas-event-form', plugins_url( '/assets/js/event-form.js', __FILE__ ), [ 'jquery' ], false, true );
		wp_register_script( 'nas-participator-list', plugins_url( '/assets/js/nas-participator-list.js', __FILE__ ), [ 'jquery' ], false, true );
		wp_register_script( 'sweetalert2', plugins_url( '/assets/js/sweetalert2.all.min.js', __FILE__ ), [ 'jquery' ], false, true );
		wp_register_script( 'nas-mask-phone', plugins_url( '/assets/js/jquery.inputmask.bundle.min.js', __FILE__ ), [ 'jquery' ], false, true );
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
		add_filter( 'script_loader_tag', [ $this, 'editor_scripts_as_a_module' ], 10, 2 );

		wp_enqueue_script(
			'elementor-hello-world-editor',
			plugins_url( '/assets/js/editor/editor.js', __FILE__ ),
			[
				'elementor-editor',
			],
			'1.2.1',
			true
		);
	}

	public function load_admin_scripts(){
		$suffix                    = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
        // Unfortunately we can't just enqueue our scripts here - it's too early. So register against the proper action hook to do it
         wp_enqueue_script( 'my-script', plugins_url( '/assets/js/admin/app.js', __FILE__ ), [ 'jquery' ], false, true );
		 wp_enqueue_script( 'sweetalert2', plugins_url( '/assets/js/sweetalert2.all.min.js', __FILE__ ), [ 'jquery' ], false, true );
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
	public function editor_scripts_as_a_module( $tag, $handle ) {
		if ( 'elementor-hello-world-editor' === $handle ) {
			$tag = str_replace( '<script', '<script type="module"', $tag );
		}

		return $tag;
	}

	public function widget_styles() {

		wp_register_style( 'nas-participator-list', plugins_url( 'assets/css/nas-participator-list.css', __FILE__ ) );

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
		require_once( __DIR__ . '/widgets/event-form.php' );
		require_once( __DIR__ . '/widgets/participator-list.php' );
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function register_widgets() {
		// Its is now safe to include Widgets files
		$this->include_widgets_files();
	

		// Register Widgets
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Event_Form() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Participator_List() );
		  // Add element category in panel
       
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
            'nas-academy', // This is the name of your addon's category and will be used to group your widgets/elements in the Edit sidebar pane!
            [
                'title' => __('NAS Drawing Academy', 'sky-elementor-addons'), // The title of your modules category - keep it simple and short!
                'icon'  => 'font',
            ], 1
        );
    }

	public function __construct() {
		
		add_action('elementor/init', [$this, 'elementor_init']);

		// Register widget scripts
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );

		// Register widgets
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );

		// Register editor scripts
		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'editor_scripts' ] );

		// admin js
		add_action( 'admin_enqueue_scripts', [$this, 'load_admin_scripts'] );

		// Register Widget Styles
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'widget_styles' ] );

		
		 
	}
}

// Instantiate Plugin Class
Plugin::instance();
