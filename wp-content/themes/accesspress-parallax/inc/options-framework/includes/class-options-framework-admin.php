<?php
/**
 * @package   Options_Framework
 * @author    Devin Price <devin@wptheming.com>
 * @license   GPL-2.0+
 * @link      http://wptheming.com
 * @copyright 2010-2014 WP Theming
 */

class Options_Framework_Admin {

	/**
     * Page hook for the options screen
     *
     * @since 1.7.0
     * @type string
     */
    protected $options_screen = null;

    /**
     * Hook in the scripts and styles
     *
     * @since 1.7.0
     */
    public function init() {

		// Gets options to load
    	$options = & Options_Framework::_optionsframework_options();

		// Checks if options are available
    	if ( $options ) {

			// Add the options page and menu item.
			add_action( 'admin_menu', array( $this, 'add_custom_options_page' ) );

			// Add the required scripts and styles
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

			// Settings need to be registered after admin_init
			add_action( 'admin_init', array( $this, 'settings_init' ) );

			// Adds options menu to the admin bar
			add_action( 'wp_before_admin_bar_render', array( $this, 'optionsframework_admin_bar' ) );

		}

    }

	/**
     * Registers the settings
     *
     * @since 1.7.0
     */
    function settings_init() {

    	// Get the option name
		$options_framework = new Options_Framework;
	    $name = $options_framework->get_option_name();

		// Registers the settings fields and callback
		register_setting( 'optionsframework', $name,  array ( $this, 'validate_options' ) );

		// Displays notice after options save
		add_action( 'optionsframework_after_validate', array( $this, 'save_options_notice' ) );

    }

	static function menu_settings() {

		$menu = array(

			// Modes: submenu, menu
            'mode' => 'submenu',

            // Submenu default settings
            'page_title' => '',
			'menu_title' => esc_html__('Theme Options', 'accesspress-parallax'),
			'capability' => 'edit_theme_options',
			'menu_slug' => 'theme-options',
            'parent_slug' => 'themes.php',

            // Menu default settings
            'icon_url' => 'dashicons-admin-generic',
            'position' => '61'

		);

		return apply_filters( 'optionsframework_menu', $menu );
	}

	/**
     * Add a subpage called "Theme Options" to the appearance menu.
     *
     * @since 1.7.0
     */
	function add_custom_options_page() {

		$menu = $this->menu_settings();

		// If you want a top level menu, see this Gist:
		// https://gist.github.com/devinsays/884d6abe92857a329d99

		// Code removed because it conflicts with .org theme check.

		$this->options_screen = add_theme_page(
            	$menu['page_title'],
            	$menu['menu_title'],
            	$menu['capability'],
            	$menu['menu_slug'],
            	array( $this, 'options_page' )
        );

	}

	/**
     * Loads the required stylesheets
     *
     * @since 1.7.0
     */

	function enqueue_admin_styles( $hook ) {

		if ( $this->options_screen != $hook )
	        return;
	    wp_enqueue_style( 'accesspress-parallax-font-awesome', esc_url(get_template_directory_uri() . '/css/fontawesome/css/font-awesome.min.css') );
		wp_enqueue_style( 'optionsframework', OPTIONS_FRAMEWORK_DIRECTORY . 'css/optionsframework.css', array(),  Options_Framework::VERSION );
		wp_enqueue_style( 'wp-color-picker' );
	}

	/**
     * Loads the required javascript
     *
     * @since 1.7.0
     */
	function enqueue_admin_scripts( $hook ) {

		if ( $this->options_screen != $hook )
	        return;

		// Enqueue custom option panel JS
		wp_enqueue_script( 'options-custom', OPTIONS_FRAMEWORK_DIRECTORY . 'js/options-custom.js', array( 'jquery','wp-color-picker' ), Options_Framework::VERSION );

		// Inline scripts from options-interface.php
		add_action( 'admin_head', array( $this, 'of_admin_head' ) );
	}

	function of_admin_head() {
		// Hook to add custom scripts
		do_action( 'optionsframework_custom_scripts' );
	}

	/**
     * Builds out the options panel.
     *
	 * If we were using the Settings API as it was intended we would use
	 * do_settings_sections here.  But as we don't want the settings wrapped in a table,
	 * we'll call our own custom optionsframework_fields.  See options-interface.php
	 * for specifics on how each individual field is generated.
	 *
	 * Nonces are provided using the settings_fields()
	 *
     * @since 1.7.0
     */
	 function options_page() { ?>

		<div id="optionsframework-wrap" class="wrap">

		<?php $menu = $this->menu_settings(); ?>

		<div class="theme-header clearfix">
		<div class="accesspresslite-logo">
		<img src="<?php echo esc_url(get_template_directory_uri().'/inc/options-framework/images/logo.png') ?>" alt="<?php esc_attr_e('AccessPress Lite','accesspress-parallax'); ?>" />
		</div>

		<div class="ak-socials">
		<a target='_blank' href="<?php echo esc_url('https://accesspressthemes.com/theme-demos/?theme=accesspress-parallax'); ?>"><?php esc_html_e('Demo','accesspress-parallax'); ?></a>
		<a target='_blank' href="<?php echo esc_url('https://accesspressthemes.com/documentation/theme-instruction-accesspress-parallax/'); ?>"><?php esc_html_e('Documentation/Video Tutorial','accesspress-parallax'); ?></a>
		</div>
		</div>


	    <div class="nav-tab-wrapper">
	        <?php echo Options_Framework_Interface::optionsframework_tabs(); ?>
	    </div>

	    
	    <div id="optionsframework-metabox" class="metabox-holder">
	    <?php settings_errors( 'options-framework' ); ?>
		    <div id="optionsframework" class="postbox">
				<form action="options.php" method="post">
				<?php settings_fields( 'optionsframework' ); ?>
				<?php Options_Framework_Interface::optionsframework_fields(); /* Settings */ ?>
				<div id="optionsframework-submit">
					<input type="submit" class="button-primary" name="update" value="<?php esc_attr_e( 'Save Options', 'accesspress-parallax' ); ?>" />
					<input type="submit" class="reset-button button-secondary" name="reset" value="<?php esc_attr_e( 'Restore Defaults', 'accesspress-parallax' ); ?>" onclick="return confirm( '<?php print esc_js( esc_html__( 'Click OK to reset. Any theme settings will be lost!', 'accesspress-parallax' ) ); ?>' );" />
					<div class="clear"></div>
				</div>
				</form>
			</div> <!-- / #container -->
            
            <div class="update-banner">
            	<h3><?php esc_html_e('Upgrade to Parallax Pro','accesspress-parallax') ?></h3>
    			<img src="<?php echo esc_url(get_template_directory_uri().'/inc/options-framework/images/upgrade-top.jpg') ?>">
    			<div class="button-link">
    				<a href="<?php echo esc_url('https://accesspressthemes.com/theme-demos/?theme=accesspress-parallax-pro'); ?>" target="_blank"><img src="<?php echo esc_url(get_template_directory_uri().'/inc/options-framework/images/demo-btn.png') ?>"></a>
    				<a href="<?php echo esc_url('https://accesspressthemes.com/wordpress-themes/accesspress-parallax-pro/'); ?>" target="_blank"><img src="<?php echo esc_url(get_template_directory_uri().'/inc/options-framework/images/upgrade-btn.png') ?>"></a>
    			</div>
    			<div class="any-question">
    				<?php echo sprintf( wp_kses_post('Any question!! Click <a href="%s" target="_blank">here</a> for Live Chat.','accesspress-parallax'), esc_url('https://accesspressthemes.com/contact/')); ?>
    			</div>
    			<h3 class="pro-feature-title"><?php esc_html_e('Pro Features','accesspress-parallax') ?><span>&#8250;</span></h3>
    			<div class="feature-img">
    			<img src="<?php echo esc_url(get_template_directory_uri().'/inc/options-framework/images/upgrade-bottom.jpg') ?>">
    			<div class="button-link">
    				<a href="<?php echo esc_url('https://accesspressthemes.com/theme-demos/?theme=accesspress-parallax-pro'); ?>" target="_blank"><img src="<?php echo esc_url(get_template_directory_uri().'/inc/options-framework/images/demo-btn.png') ?>"></a>
    				<a href="<?php echo esc_url('https://accesspressthemes.com/wordpress-themes/accesspress-parallax-pro/'); ?>" target="_blank"><img src="<?php echo esc_url(get_template_directory_uri().'/inc/options-framework/images/upgrade-btn.png') ?>"></a>
    			</div>
    			</div>
    		</div>
		</div>
		<?php do_action( 'optionsframework_after' ); ?>
		</div> <!-- / .wrap -->

	<?php
	}

	/**
	 * Validate Options.
	 *
	 * This runs after the submit/reset button has been clicked and
	 * validates the inputs.
	 *
	 * @uses $_POST['reset'] to restore default options
	 */
	function validate_options( $input ) {

		/*
		 * Restore Defaults.
		 *
		 * In the event that the user clicked the "Restore Defaults"
		 * button, the options defined in the theme's options.php
		 * file will be added to the option for the active theme.
		 */

		if ( isset( $_POST['reset'] ) ) {
			add_settings_error( 'options-framework', 'restore_defaults', esc_html__( 'Default options restored.', 'accesspress-parallax' ), 'updated fade' );
			return $this->get_default_values();
		}

		/*
		 * Update Settings
		 *
		 * This used to check for $_POST['update'], but has been updated
		 * to be compatible with the theme customizer introduced in WordPress 3.4
		 */

		$clean = array();
		$options = & Options_Framework::_optionsframework_options();
		foreach ( $options as $option ) {

			if ( ! isset( $option['id'] ) ) {
				continue;
			}

			if ( ! isset( $option['type'] ) ) {
				continue;
			}

			$id = preg_replace( '/[^a-zA-Z0-9._\-]/', '', strtolower( $option['id'] ) );

			// Set checkbox to false if it wasn't sent in the $_POST
			if ( 'checkbox' == $option['type'] && ! isset( $input[$id] ) ) {
				$input[$id] = false;
			}

			// Set parallax to '' if it wasn't sent in the $_POST
			if ( 'parallaxsection' == $option['type'] && ! isset( $input[$id] ) ) {
				$input[$id] = '';
			}

			// Set each item in the multicheck to false if it wasn't sent in the $_POST
			if ( 'multicheck' == $option['type'] && ! isset( $input[$id] ) ) {
				foreach ( $option['options'] as $key => $value ) {
					$input[$id][$key] = false;
				}
			}

			// For a value to be submitted to database it must pass through a sanitization filter
			if ( has_filter( 'of_sanitize_' . $option['type'] ) ) {
				$clean[$id] = apply_filters( 'of_sanitize_' . $option['type'], $input[$id], $option );
			}
		}

		// Hook to run after validation
		do_action( 'optionsframework_after_validate', $clean );

		return $clean;
	}

	/**
	 * Display message when options have been saved
	 */

	function save_options_notice() {
		add_settings_error( 'options-framework', 'save_options', esc_html__( 'Options saved.', 'accesspress-parallax' ), 'updated fade' );
	}

	/**
	 * Get the default values for all the theme options
	 *
	 * Get an array of all default values as set in
	 * options.php. The 'id','std' and 'type' keys need
	 * to be defined in the configuration array. In the
	 * event that these keys are not present the option
	 * will not be included in this function's output.
	 *
	 * @return array Re-keyed options configuration array.
	 *
	 */

	function get_default_values() {
		$output = array();
		$config = & Options_Framework::_optionsframework_options();
		foreach ( (array) $config as $option ) {
			if ( ! isset( $option['id'] ) ) {
				continue;
			}
			if ( ! isset( $option['std'] ) ) {
				continue;
			}
			if ( ! isset( $option['type'] ) ) {
				continue;
			}
			if ( has_filter( 'of_sanitize_' . $option['type'] ) ) {
				$output[$option['id']] = apply_filters( 'of_sanitize_' . $option['type'], $option['std'], $option );
			}
		}
		return $output;
	}

	/**
	 * Add options menu item to admin bar
	 */

	function optionsframework_admin_bar() {

		$menu = $this->menu_settings();

		global $wp_admin_bar;

		if ( 'menu' == $menu['mode'] ) {
			$href = admin_url( 'admin.php?page=' . $menu['menu_slug'] );
		} else {
			$href = admin_url( 'themes.php?page=' . $menu['menu_slug'] );
		}

		$args = array(
			'parent' => 'appearance',
			'id' => 'of_theme_options',
			'title' => $menu['menu_title'],
			'href' => $href
		);

		$wp_admin_bar->add_menu( apply_filters( 'optionsframework_admin_bar', $args ) );
	}

}
